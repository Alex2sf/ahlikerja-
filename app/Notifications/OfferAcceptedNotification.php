<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Offer;

class OfferAcceptedNotification extends Notification
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
            'message' => "Kamu berhasil memenangkan tugas postingan '{$this->offer->post->judul}'.",
            'post_id' => $this->offer->post->id,
            'url' => route('posts.all'), // Mengarahkan ke halaman semua postingan
        ];
    }
}
