<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Offer;

class OfferNotSelectedNotification extends Notification
{
    use Queueable;

    protected $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Maaf, kamu belum terpilih untuk menjadi pemenang postingan tugas ini.",
            'post_id' => $this->offer->post->id,
            'url' => route('posts.all'), // Mengarahkan ke halaman semua postingan
        ];
    }
}
