<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function complete(Request $request, $orderId)
    {
        Log::info('Complete button clicked for Order', ['order_id' => $orderId, 'user_id' => Auth::id()]);

        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to complete order', ['order_id' => $orderId, 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menandai pemesanan ini selesai.');
        }

        $updated = $order->update(['is_completed' => true]);
        Log::info('Order update attempted', ['order_id' => $orderId, 'updated' => $updated]);

        if ($updated) {
            return redirect()->back()->with('success', 'Pemesanan telah ditandai selesai. Silakan beri rating dan ulasan.');
        } else {
            Log::error('Failed to update order', ['order_id' => $orderId]);
            return redirect()->back()->with('error', 'Gagal menandai pemesanan selesai.');
        }
    }
    public function storeReview(Request $request, $orderId)
    {
        Log::info('Store review attempt', ['order_id' => $orderId, 'user_id' => Auth::id()]);

        $order = Order::find($orderId);
        $booking = Booking::find($orderId);

        if ($order) {
            $entity = $order;
            $reviewData = ['order_id' => $order->id, 'booking_id' => null];
        } elseif ($booking) {
            $entity = $booking;
            $reviewData = ['booking_id' => $booking->id, 'order_id' => null];
        } else {
            Log::error('Order or Booking not found for review', ['id' => $orderId]);
            return redirect()->back()->with('error', 'Pemesanan tidak ditemukan.');
        }

        if ($entity->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to review', ['id' => $orderId, 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengulas pemesanan ini.');
        }
        if (!$entity->is_completed) {
            return redirect()->back()->with('error', 'Pemesanan ini belum selesai.');
        }
        if ($entity->review) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk pemesanan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);

        $review = Review::create(array_merge($reviewData, [
            'user_id' => Auth::id(),
            'contractor_id' => $entity->contractor_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]));

        Log::info('Review created', ['review_id' => $review->id]);

        return redirect()->back()->with('success', 'Rating dan ulasan berhasil disimpan.');
    }
}
