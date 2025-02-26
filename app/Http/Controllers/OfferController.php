<?php
namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{


    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $contractor = Auth::user();

        // Pastikan user adalah kontraktor
        if ($contractor->role !== 'kontraktor') {
            return redirect()->back()->with('error', 'Hanya kontraktor yang dapat memberikan penawaran.');
        }

        // Cek apakah kontraktor sudah memberikan penawaran untuk postingan ini
        if ($post->offers()->where('contractor_id', $contractor->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penawaran untuk postingan ini.');
        }

        Offer::create([
            'contractor_id' => $contractor->id,
            'post_id' => $post->id,
            'accepted' => false
        ]);

        return redirect()->back()->with('success', 'Penawaran berhasil dikirim!');
    }

    public function accept($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $user = Auth::user();

        // Pastikan user adalah pemilik postingan
        if ($offer->post->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menerima penawaran ini.');
        }

        // Set semua penawaran lain untuk postingan ini menjadi tidak diterima
        Offer::where('post_id', $offer->post->id)->update(['accepted' => false]);

        // Set penawaran ini sebagai diterima
        $offer->update(['accepted' => true]);

        // Buat pemesanan otomatis (seperti yang sudah ada sebelumnya)
        \App\Models\Order::create([
            'user_id' => $user->id,
            'contractor_id' => $offer->contractor_id,
            'post_id' => $offer->post_id,
            'offer_id' => $offer->id
        ]);

        return redirect()->back()->with('success', 'Penawaran dari kontraktor telah diterima dan ditambahkan ke keranjang pemesanan.');
    }

    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        $offers = $post->offers()->with('contractor.contractorProfile')->get();
        $user = Auth::user();

        // Pastikan user adalah pemilik postingan
        if ($post->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melihat penawaran ini.');
        }

        // Cek apakah sudah ada penawaran yang diterima untuk postingan ini
        $acceptedOffer = $post->offers()->where('accepted', true)->first();

        return view('offers.index', compact('post', 'offers', 'acceptedOffer'));
    }
}
