<?php
namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OfferReceivedNotification;
use App\Notifications\OfferAcceptedNotification;
use App\Notifications\OfferNotSelectedNotification;

class OfferController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $contractor = Auth::user();

        // Cek jika user adalah kontraktor dan belum disetujui
        if ($contractor->role !== 'kontraktor' || (!$contractor->contractorProfile || !$contractor->contractorProfile->approved)) {
            return redirect()->back()->with('error', 'Anda harus disetujui oleh admin terlebih dahulu untuk memberikan penawaran.');
        }

        // Cek apakah kontraktor sudah memberikan penawaran untuk postingan ini
        if ($post->offers()->where('contractor_id', $contractor->id)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penawaran untuk postingan ini.');
        }

        // Buat penawaran
        $offer = Offer::create([
            'contractor_id' => $contractor->id,
            'post_id' => $post->id,
            'accepted' => false,
            'status' => 'pending'
        ]);

        // Kirim notifikasi ke user (pemilik postingan)
        $post->user->notify(new OfferReceivedNotification($offer));

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

        // Ambil semua penawaran lain untuk postingan ini (kecuali penawaran yang diterima)
        $otherOffers = Offer::where('post_id', $offer->post->id)
            ->where('id', '!=', $offer->id)
            ->get();

        // Set semua penawaran lain menjadi tidak diterima dan status 'not_selected'
        Offer::where('post_id', $offer->post->id)
            ->where('id', '!=', $offer->id)
            ->update([
                'accepted' => false,
                'status' => 'not_selected',
            ]);

        // Kirim notifikasi ke kontraktor yang tidak terpilih
        foreach ($otherOffers as $otherOffer) {
            $otherOffer->contractor->notify(new OfferNotSelectedNotification($otherOffer));
        }

        // Set penawaran ini sebagai diterima dan status 'accepted'
        $offer->update([
            'accepted' => true,
            'status' => 'accepted',
        ]);

        // Ubah status postingan menjadi 'closed'
        $offer->post->update(['status' => 'closed']);

        // Buat pemesanan otomatis
        \App\Models\Order::create([
            'user_id' => $user->id,
            'contractor_id' => $offer->contractor_id,
            'post_id' => $offer->post_id,
            'offer_id' => $offer->id,
        ]);

        // Kirim notifikasi ke kontraktor yang penawarannya diterima
        $offer->contractor->notify(new OfferAcceptedNotification($offer));

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
        $acceptedOffer = $post->offers()->where('status', 'accepted')->first();

        return view('offers.index', compact('post', 'offers', 'acceptedOffer'));
    }

    public function myOffers()
    {
        $contractor = Auth::user();

        // Pastikan user adalah kontraktor dan disetujui
        if ($contractor->role !== 'kontraktor' || (!$contractor->contractorProfile || !$contractor->contractorProfile->approved)) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $offers = Offer::where('contractor_id', $contractor->id)
                      ->with('post.user')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('offers.my-offers', compact('offers'));
    }
}
