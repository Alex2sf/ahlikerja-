<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingStatusUpdatedNotification;
use App\Notifications\PaymentStageUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create($contractorId)
    {
        $contractor = \App\Models\User::findOrFail($contractorId);

        if ($contractor->role !== 'kontraktor') {
            return redirect()->route('home')->with('error', 'Pengguna ini bukan kontraktor.');
        }

        if (!\App\Http\Controllers\ProfileController::isProfileComplete(Auth::user())) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        return view('bookings.create', compact('contractor'));
    }

    public function finalApprove($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        if ($booking->status !== 'accepted' || $booking->final_approve) {
            return redirect()->route('bookings.index')->with('error', 'Aksi tidak diizinkan.');
        }

        $booking->update(['final_approve' => true]);

        $booking->contractor->notify(new BookingStatusUpdatedNotification(
            $booking,
            'final_approve',
            null,
            "User {$booking->user->name} telah memberikan final approve untuk pesanan '{$booking->judul}'."
        ));

        return redirect()->route('bookings.index')->with('success', 'Pesanan telah disetujui secara final!');
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

        if (!\App\Http\Controllers\ProfileController::isProfileComplete($user)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
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
            'status' => 'pending',
            'deadline' => Carbon::now()->addHours(24),
        ]);

        $contractor->notify(new BookingCreatedNotification($booking));

        return redirect()->route('bookings.index')->with('success', 'Pesanan berhasil dibuat dan menunggu persetujuan kontraktor dalam 24 jam!');
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

            foreach ($bookings as $booking) {
                if ($booking->status === 'pending' && $booking->deadline && now()->greaterThan($booking->deadline)) {
                    $booking->update(['status' => 'expired']);
                    $booking->user->notify(new BookingStatusUpdatedNotification(
                        $booking,
                        'expired',
                        null,
                        "Pesanan '{$booking->judul}' telah hangus karena tidak ada tindakan dalam 24 jam."
                    ));
                }
            }

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
            'response_file' => 'nullable|required_if:status,accepted|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'declined') {
            $data['decline_reason'] = $request->decline_reason;
            $data['response_file'] = null;
        } else {
            $data['decline_reason'] = null;
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

        if ($booking->is_completed) {
            return redirect()->back()->with('error', 'Pemesanan ini sudah ditandai selesai.');
        }

        // Validasi input untuk pembayaran terakhir dan review
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
            'pembayaran' => 'nullable|image|max:2048',
        ]);

        $stage = $booking->payment_stage + 1;
        if ($stage > 4) {
            $stage = 4;
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs("payments/booking/stage_{$stage}", $fileName, 'public');
            $column = "payment_proof_{$stage}";
            $booking->update([
                $column => $path,
                'payment_stage' => $stage,
                'is_completed' => true
            ]);
        }

        $reviewData = [
            'booking_id' => $booking->id,
            'order_id' => null,
            'user_id' => Auth::id(),
            'contractor_id' => $booking->contractor_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ];

        if ($request->hasFile('pembayaran')) {
            $file = $request->file('pembayaran');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs('reviews/pembayaran', $fileName, 'public');
            $reviewData['pembayaran'] = $path;
        }

        $review = Review::create($reviewData);
        Log::info('Review created', ['review_id' => $review->id]);

        $booking->contractor->notify(new PaymentStageUpdatedNotification($booking, $stage, 'booking', true));

        return redirect()->back()->with('success', 'Pemesanan telah selesai. Bukti pembayaran terakhir dan ulasan telah disimpan.');
    }
}
