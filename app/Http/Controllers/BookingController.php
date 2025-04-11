<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create($contractorId)
    {
        $contractor = \App\Models\User::findOrFail($contractorId);

        if ($contractor->role !== 'kontraktor') {
            return redirect()->route('home')->with('error', 'Pengguna ini bukan kontraktor.');
        }

        return view('bookings.create', compact('contractor'));
    }

    public function store(Request $request, $contractorId)
    {
        $user = Auth::user();
        $contractor = \App\Models\User::findOrFail($contractorId);

        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Hanya user yang dapat memesan kontraktor.');
        }
        if ($contractor->role !== 'kontraktor') {
            return redirect()->back()->with('error', 'Pengguna ini bukan kontraktor.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|max:2048',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric',
            'durasi' => 'required|string|max:100'
        ]);

        $data = $request->only(['judul', 'deskripsi', 'lokasi', 'estimasi_anggaran', 'durasi']);

        if ($request->hasFile('gambar')) {
            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $path = Storage::disk('public')->putFileAs('bookings', $file, $fileName);
                $gambarPaths[] = $path;
            }
            $data['gambar'] = $gambarPaths;
        }

        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen');
            $fileName = time() . '_' . uniqid() . '.' . $dokumen->extension();
            $path = Storage::disk('public')->putFileAs('bookings/dokumen', $dokumen, $fileName);
            $data['dokumen'] = $path;
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'contractor_id' => $contractor->id,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'gambar' => $data['gambar'] ?? null,
            'dokumen' => $data['dokumen'] ?? null,
            'lokasi' => $data['lokasi'],
            'estimasi_anggaran' => $data['estimasi_anggaran'],
            'durasi' => $data['durasi'],
            'status' => 'pending'
        ]);

        $contractor->notify(new BookingCreatedNotification($booking));

        return redirect()->route('bookings.index')->with('success', 'Pesanan berhasil dibuat dan menunggu persetujuan kontraktor!');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'user') {
            $bookings = Booking::where('user_id', $user->id)
                              ->with('contractor.contractorProfile')
                              ->orderBy('created_at', 'desc')
                              ->get();
            return view('bookings.index', compact('bookings'));
        } elseif ($user->role === 'kontraktor') {
            $bookings = Booking::where('contractor_id', $user->id)
                              ->with('user.profile')
                              ->orderBy('created_at', 'desc')
                              ->get();
            Log::info('Bookings fetched for contractor', ['user_id' => $user->id, 'bookings' => $bookings->toArray()]);
            return view('bookings.contractor', compact('bookings'));
        }

        return redirect()->route('home')->with('error', 'Role tidak valid.');
    }

    public function updateStatus(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $contractor = Auth::user();

        if ($contractor->role !== 'kontraktor' || $booking->contractor_id !== $contractor->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah status pesanan ini.');
        }

        $request->validate([
            'status' => 'required|in:accepted,declined',
            'decline_reason' => 'required_if:status,declined|string|max:1000',
            'response_file' => 'nullable|required_if:status,accepted|file|mimes:pdf,doc,docx|max:5120', // Validasi response_file
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'declined') {
            $data['decline_reason'] = $request->decline_reason;
            $data['response_file'] = null; // Hapus response_file jika ditolak
        } else {
            $data['decline_reason'] = null;
            // Handle response_file (single file) saat diterima
            if ($request->hasFile('response_file')) {
                $responseFile = $request->file('response_file');
                $fileName = time() . '_' . uniqid() . '.' . $responseFile->extension();
                $path = Storage::disk('public')->putFileAs('bookings/response_files', $responseFile, $fileName);
                $data['response_file'] = $path;
            }
        }

        $booking->update($data);

        $booking->user->notify(new BookingStatusUpdatedNotification($booking, $request->status, $request->decline_reason));

        $notification = $request->status === 'accepted'
            ? 'Pesanan Anda telah diterima oleh kontraktor dan ditambahkan ke keranjang pemesanan.'
            : 'Pesanan Anda telah ditolak oleh kontraktor. Alasan: ' . ($request->decline_reason ?? 'Tidak ada alasan');

        return redirect()->route('bookings.index')->with('success', $notification);
    }

    public function complete(Request $request, $bookingId)
    {
        Log::info('Complete button clicked for Booking', ['booking_id' => $bookingId, 'user_id' => Auth::id()]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to complete booking', ['booking_id' => $bookingId, 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menandai pemesanan ini selesai.');
        }

        $updated = $booking->update(['is_completed' => true]);
        Log::info('Booking update attempted', ['booking_id' => $bookingId, 'updated' => $updated]);

        if ($updated) {
            return redirect()->back()->with('success', 'Pemesanan telah ditandai selesai. Silakan beri rating dan ulasan.');
        } else {
            Log::error('Failed to update booking', ['booking_id' => $bookingId]);
            return redirect()->back()->with('error', 'Gagal menandai pemesanan ini selesai.');
        }
    }
}
