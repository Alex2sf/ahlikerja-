<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Routing\Controller; // Tambahkan ini!
use Illuminate\Support\Facades\Log;


class SubscriptionController extends Controller
{

    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    public function create()
    {
        $contractor = Auth::user();
        return view('subscriptions.create', compact('contractor'));
    }

    public function store(Request $request)
    {
        $contractor = Auth::user();

        // Validasi request
        $request->validate([
            'plan_id' => 'required', // Hanya cek apakah ada, tanpa validasi exists
        ]);

        // Buat order_id unik
        $orderId = 'SUBS-' . uniqid() . '-' . $contractor->id;

        // Parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 1, // Rp1 (untuk testing)
            ],
            'customer_details' => [
                'first_name' => $contractor->name,
                'email' => $contractor->email,
            ],
            'item_details' => [
                [
                    'id' => 'SUBS-001',
                    'price' => 1,
                    'quantity' => 1,
                    'name' => 'Akses Postingan Tugas (1 Bulan)'
                ]
            ]
        ];

        // Dapatkan Snap Token dari Midtrans dengan handling error yang lebih detail
        try {
            // Pastikan konfigurasi Midtrans sudah benar sebelum request
            if (empty(Config::$serverKey)) {
                throw new \Exception('Midtrans server key is not configured. Please check your .env file.');
            }

            $snapToken = Snap::getSnapToken($params);

            // Log sukses mendapatkan snap token
            Log::info('Midtrans Snap Token Generated Successfully:', [
                'order_id' => $orderId,
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            // Log error dengan detail lengkap, termasuk response dari Midtrans
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();
            $errorResponse = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response';

            Log::error('Midtrans Snap Token Error:', [
                'message' => $errorMessage,
                'code' => $errorCode,
                'response' => $errorResponse,
                'order_id' => $orderId
            ]);

            return redirect()->back()
                ->with('error', 'Gagal membuat transaksi: ' . $errorMessage)
                ->withInput();
        }

        // Simpan subscription sementara (belum aktif sampai pembayaran dikonfirmasi)
        try {
            $subscription = Subscription::create([
                'contractor_id' => $contractor->id,
                'start_date' => now(),
                'end_date' => null,
                'transaction_id' => $orderId,
                'is_active' => false,
                'status' => 'pending'
            ]);

            // Log sukses menyimpan subscription
            Log::info('Subscription Created Successfully:', [
                'subscription_id' => $subscription->id,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription Creation Error:', [
                'message' => $e->getMessage(),
                'order_id' => $orderId
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menyimpan data berlangganan. Silakan coba lagi.')
                ->withInput();
        }

        // Redirect ke halaman checkout dengan snap token dan subscription
        return view('subscriptions.checkout', compact('snapToken', 'subscription'));
    }
    public function callback(Request $request)
{
    // Buat instance notifikasi Midtrans
    $notif = new \Midtrans\Notification();
    // Ambil data transaksi dari notifikasi
    $transactionStatus = $notif->transaction_status;
    $orderId = $notif->order_id;
    $paymentType = $notif->payment_type;
    $fraudStatus = $notif->fraud_status;

    // Cari subscription berdasarkan order_id
    $subscription = Subscription::where('transaction_id', $orderId)->first();

    if (!$subscription) {
        Log::warning('Subscription not found for order_id:', ['order_id' => $orderId]);
        return response()->json(['status' => 'error', 'message' => 'Subscription not found'], 404);
    }

    // Handle status transaksi
    if ($transactionStatus == 'capture') {
        if ($paymentType == 'credit_card') {
            if ($fraudStatus == 'challenge') {
                // Transaksi dalam proses challenge
                $subscription->update(['status' => 'challenge']);
            } else {
                // Transaksi berhasil
                $subscription->update([
                    'is_active' => true,
                    'end_date' => now()->addMonth(),
                    'status' => 'active'
                ]);
            }
        }
    } elseif ($transactionStatus == 'settlement') {
        // Transaksi berhasil
        $subscription->update([
            'is_active' => true,
            'end_date' => now()->addMonth(),
            'status' => 'active'
        ]);
    } elseif ($transactionStatus == 'pending') {
        // Transaksi masih pending
        $subscription->update(['status' => 'pending']);
    } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
        // Transaksi gagal
        $subscription->update([
            'is_active' => false,
            'status' => $transactionStatus
        ]);
    }

    // Log hasil pembaruan
    Log::info('Subscription updated:', [
        'order_id' => $orderId,
        'status' => $subscription->status,
        'is_active' => $subscription->is_active,
        'end_date' => $subscription->end_date
    ]);

    return response()->json(['status' => 'success']);
}
    public function success()
    {
        return view('subscriptions.success');
    }

    public function failed()
    {
        return view('subscriptions.failed');
    }

    public function checkSubscription()
    {
        $contractor = Auth::user();
        $subscription = Subscription::where('contractor_id', $contractor->id)
                                    ->where('is_active', true)
                                    ->where('end_date', '>', now())
                                    ->orderBy('end_date', 'desc')
                                    ->first();

        if ($subscription) {
            return response()->json(['status' => 'active', 'end_date' => $subscription->end_date]);
        }

        return response()->json(['status' => 'inactive']);
    }
}
