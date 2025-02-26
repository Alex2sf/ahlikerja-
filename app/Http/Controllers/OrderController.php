<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function store($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $user = Auth::user();

        // Pastikan user adalah pemilik postingan
        if ($offer->post->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk membuat pemesanan ini.');
        }

        // Pastikan tawaran sudah diterima
        if (!$offer->accepted) {
            return redirect()->back()->with('error', 'Tawaran ini belum diterima, silakan terima tawaran terlebih dahulu.');
        }

        // Cek apakah pemesanan sudah ada untuk tawaran ini
        if (Order::where('offer_id', $offer->id)->exists()) {
            return redirect()->back()->with('error', 'Pemesanan untuk tawaran ini sudah ada.');
        }

        Order::create([
            'user_id' => $user->id,
            'contractor_id' => $offer->contractor_id,
            'post_id' => $offer->post_id,
            'offer_id' => $offer->id
        ]);

        return redirect()->route('orders.index')->with('success', 'Pemesanan berhasil ditambahkan ke keranjang!');
    }
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'user') {
            // Pemesanan dari postingan (orders)
            $postOrders = Order::where('user_id', $user->id)
                              ->with('contractor.contractorProfile', 'post')
                              ->get();
            // Pemesanan langsung ke kontraktor (bookings dengan status accepted)
            $bookingOrders = Booking::where('user_id', $user->id)
                                  ->with('contractor.contractorProfile')
                                  ->where('status', 'accepted')
                                  ->get();
            return view('orders.index', compact('postOrders', 'bookingOrders'));
        } elseif ($user->role === 'kontraktor') {
            $orders = Order::where('contractor_id', $user->id)
                          ->with('user.profile', 'post')
                          ->get();
            return view('orders.contractor', compact('orders'));
        }

        return redirect()->route('home')->with('error', 'Role tidak valid.');
    }
}
