<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PaymentStageUpdatedNotification; // Tambahkan import

class OrderController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $contractor = Auth::user();

        if ($contractor->role !== 'kontraktor') {
            return redirect()->back()->with('error', 'Hanya kontraktor yang dapat memberikan penawaran.');
        }

        if ($post->offers()->where('contractor_id', $contractor->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penawaran untuk postingan ini.');
        }

        $offer = Offer::create([
            'contractor_id' => $contractor->id,
            'post_id' => $post->id,
            'accepted' => false
        ]);

        return redirect()->back()->with('success', 'Penawaran berhasil dikirim!');
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'user') {
            $postOrders = Order::where('user_id', $user->id)
                              ->with('contractor.contractorProfile', 'post')
                              ->orderBy('created_at', 'desc')
                              ->get();
            $bookingOrders = Booking::where('user_id', $user->id)
                                  ->with('contractor.contractorProfile')
                                  ->where('final_approve', true)
                                  ->orderBy('created_at', 'desc')
                                  ->get();
            return view('orders.index', compact('postOrders', 'bookingOrders'));
        } elseif ($user->role === 'kontraktor') {
            $orders = Order::where('contractor_id', $user->id)
                          ->with('user.profile', 'post', 'review')
                          ->orderBy('created_at', 'desc')
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

        if ($order->is_completed) {
            return redirect()->back()->with('error', 'Pemesanan ini sudah ditandai selesai.');
        }

        // Validasi input untuk pembayaran terakhir dan review
        $request->validate([
            'payment_proof' => 'required|image|max:2048', // Bukti pembayaran terakhir wajib
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
            'pembayaran' => 'nullable|image|max:2048', // Bukti pembayaran tambahan (opsional) untuk review
        ]);

        // Simpan bukti pembayaran terakhir
        $stage = $order->payment_stage + 1; // Tahap terakhir adalah tahap berikutnya
        if ($stage > 4) {
            $stage = 4; // Maksimal tahap 4
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs("payments/order/stage_{$stage}", $fileName, 'public');
            $column = "payment_proof_{$stage}";
            $order->update([
                $column => $path,
                'payment_stage' => $stage,
                'is_completed' => true // Tandai selesai
            ]);
        }

        // Simpan review
        $reviewData = [
            'order_id' => $order->id,
            'booking_id' => null,
            'user_id' => Auth::id(),
            'contractor_id' => $order->contractor_id,
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

        // Kirim notifikasi ke kontraktor bahwa pembayaran final telah dilakukan
        $order->contractor->notify(new PaymentStageUpdatedNotification($order, $stage, 'order', true));

        return redirect()->back()->with('success', 'Pemesanan telah selesai. Bukti pembayaran terakhir dan ulasan telah disimpan.');
    }

    public function uploadPaymentProof(Request $request, $id, $type, $stage)
    {
        Log::info('Upload payment proof attempt', ['id' => $id, 'type' => $type, 'stage' => $stage, 'user_id' => Auth::id()]);

        if ($type === 'order') {
            $entity = Order::findOrFail($id);
            $routePrefix = 'orders';
        } elseif ($type === 'booking') {
            $entity = Booking::findOrFail($id);
            $routePrefix = 'bookings';
        } else {
            return redirect()->back()->with('error', 'Tipe pemesanan tidak valid.');
        }

        if ($entity->user_id !== Auth::id()) {
            Log::warning('Unauthorized attempt to upload payment proof', ['id' => $id, 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunggah bukti pembayaran.');
        }

        if ($entity->is_completed) {
            return redirect()->back()->with('error', 'Pemesanan sudah selesai, tidak dapat mengunggah bukti pembayaran lagi.');
        }

        if ($stage < 1 || $stage > 4) {
            return redirect()->back()->with('error', 'Tahap pembayaran tidak valid.');
        }

        if ($entity->payment_stage != ($stage - 1)) {
            return redirect()->back()->with('error', 'Anda harus menyelesaikan tahap sebelumnya terlebih dahulu.');
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs("payments/{$type}/stage_{$stage}", $fileName, 'public');

            $column = "payment_proof_{$stage}";
            $entity->update([
                $column => $path,
                'payment_stage' => $stage
            ]);

            // Kirim notifikasi ke kontraktor bahwa pembayaran tahap telah diunggah
            $entity->contractor->notify(new PaymentStageUpdatedNotification($entity, $stage, $type));

            Log::info('Payment proof uploaded', ['id' => $id, 'type' => $type, 'stage' => $stage, 'path' => $path]);
            return redirect()->back()->with('success', "Bukti pembayaran tahap {$stage} berhasil diunggah.");
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
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
            'pembayaran' => 'nullable|image|max:2048',
        ]);

        $reviewData = array_merge($reviewData, [
            'user_id' => Auth::id(),
            'contractor_id' => $entity->contractor_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        if ($request->hasFile('pembayaran')) {
            $file = $request->file('pembayaran');
            $fileName = time() . '_' . uniqid() . '.' . $file->extension();
            $path = $file->storeAs('reviews/pembayaran', $fileName, 'public');
            $reviewData['pembayaran'] = $path;
        }

        $review = Review::create($reviewData);

        Log::info('Review created', ['review_id' => $review->id]);

        return redirect()->back()->with('success', 'Rating dan ulasan berhasil disimpan.');
    }
}
