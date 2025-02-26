<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{

    public function create($contractorId)
    {
        $contractor = \App\Models\User::findOrFail($contractorId);

        // Pastikan kontraktor memiliki role 'kontraktor'
        if ($contractor->role !== 'kontraktor') {
            return redirect()->route('home')->with('error', 'Pengguna ini bukan kontraktor.');
        }

        return view('bookings.create', compact('contractor'));
    }

    public function store(Request $request, $contractorId)
    {
        $user = Auth::user();
        $contractor = \App\Models\User::findOrFail($contractorId);

        // Pastikan user adalah 'user' dan kontraktor adalah 'kontraktor'
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
            'lokasi' => 'required|string|max:255',
            'estimasi_anggaran' => 'required|numeric',
            'durasi' => 'required|string|max:100'
        ]);

        $data = $request->only(['judul', 'deskripsi', 'lokasi', 'estimasi_anggaran', 'durasi']);

        // Handle gambar (multiple files)
        if ($request->hasFile('gambar')) {
            $gambarPaths = [];
            foreach ($request->file('gambar') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->storeAs('public/bookings', $fileName);
                $gambarPaths[] = $fileName;
            }
            $data['gambar'] = $gambarPaths;
        }

        Booking::create([
            'user_id' => $user->id,
            'contractor_id' => $contractor->id,
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'gambar' => $data['gambar'] ?? null,
            'lokasi' => $data['lokasi'],
            'estimasi_anggaran' => $data['estimasi_anggaran'],
            'durasi' => $data['durasi'],
            'status' => 'pending'
        ]);

        return redirect()->route('bookings.index')->with('success', 'Pesanan berhasil dibuat dan menunggu persetujuan kontraktor!');
    }
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'user') {
            $bookings = Booking::where('user_id', $user->id)
                             ->with('contractor.contractorProfile')
                             ->get();
            return view('bookings.index', compact('bookings'));
        } elseif ($user->role === 'kontraktor') {
            $bookings = Booking::where('contractor_id', $user->id)
                             ->with('user.profile')
                             ->get();
            return view('bookings.contractor', compact('bookings'));
        }

        return redirect()->route('home')->with('error', 'Role tidak valid.');
    }

    public function updateStatus(Request $request, $bookingId)
{
    $booking = Booking::findOrFail($bookingId);
    $contractor = Auth::user();

    // Pastikan user adalah kontraktor yang sesuai dengan pesanan
    if ($contractor->role !== 'kontraktor' || $booking->contractor_id !== $contractor->id) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah status pesanan ini.');
    }

    $request->validate([
        'status' => 'required|in:accepted,declined'
    ]);

    $booking->update(['status' => $request->status]);

    // Jika pesanan diterima, tambahkan ke keranjang pemesanan user
    if ($request->status === 'accepted') {
        // Pastikan tidak ada duplikat di keranjang (meskipun ini jarang terjadi karena status sudah unique)
        if (!\App\Models\Order::where('user_id', $booking->user_id)
                             ->where('contractor_id', $booking->contractor_id)
                             ->where('post_id', null) // Pastikan tidak ada post_id untuk bookings langsung
                             ->whereHas('booking', function ($query) use ($booking) {
                                 $query->where('id', $booking->id);
                             })->exists()) {
            \App\Models\Order::create([
                'user_id' => $booking->user_id,
                'contractor_id' => $booking->contractor_id,
                'post_id' => null, // Tidak ada postingan terkait, ini adalah booking langsung
                'offer_id' => null // Tidak ada offer terkait, ini adalah booking langsung
            ]);
        }
    }

    // Notifikasi untuk user
    $notification = $request->status === 'accepted'
        ? 'Pesanan Anda telah diterima oleh kontraktor dan ditambahkan ke keranjang pemesanan.'
        : 'Pesanan Anda telah ditolak oleh kontraktor.';

    return redirect()->route('bookings.index')->with('success', $notification);
}
}
