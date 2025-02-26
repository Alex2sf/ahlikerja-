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
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'kontraktor') {
                return redirect()->route('home')->with('error', 'Hanya kontraktor yang dapat berlangganan.');
            }
            return $next($request);
        });
    }

    public function create()
    {
        $contractor = Auth::user();
        return view('subscriptions.create', compact('contractor'));
    }

    public function store(Request $request)
    {
        $contractor = Auth::user();

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'SUBS-' . time() . '-' . $contractor->id,
                'gross_amount' => 1, // Rp1
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

        // Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Simpan subscription sementara (belum aktif sampai pembayaran dikonfirmasi)
        $subscription = Subscription::create([
            'contractor_id' => $contractor->id,
            'start_date' => now(),
            'end_date' => null,
            'transaction_id' => $params['transaction_details']['order_id'],
            'is_active' => false,

        ]);

        return view('subscriptions.checkout', compact('snapToken', 'subscription'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        Log::info('Midtrans callback received', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount
        ]);

        // Verifikasi status transaksi menggunakan Midtrans
        $response = json_decode(json_encode(\Midtrans\Transaction::status($orderId)));

        Log::info('Midtrans transaction status', ['response' => $response]);

        if ($response->transaction_status == 'settlement' && $response->gross_amount == $grossAmount) {
            $subscription = Subscription::where('transaction_id', $orderId)->first();

            if ($subscription) {
                Log::info('Subscription found:', ['subscription' => $subscription]);

                $subscription->update([
                    'is_active' => true,
                    'end_date' => now()->addMonth()
                ]);

                // Fetch ulang untuk memastikan update berhasil
                $updatedSubscription = Subscription::where('transaction_id', $orderId)->first();

                Log::info('Updated subscription:', [
                    'is_active' => $updatedSubscription->is_active,
                    'end_date' => $updatedSubscription->end_date
                ]);

                return response()->json(['status' => 'success']);
            } else {
                Log::warning('Subscription not found for order_id:', ['order_id' => $orderId]);
            }
        } else {
            Log::warning('Transaction not settled or amount mismatch', [
                'order_id' => $orderId,
                'transaction_status' => $response->transaction_status,
                'expected_amount' => $grossAmount,
                'received_amount' => $response->gross_amount
            ]);
        }

        return response()->json(['status' => 'failed'], 400);
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
                                  ->orderBy('end_date', 'desc')
                                  ->first();

        if ($subscription && $subscription->end_date > now()) {
            return true; // Berlangganan aktif
        }
        return false; // Berlangganan tidak aktif atau expired
    }
}
