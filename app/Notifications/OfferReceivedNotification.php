<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OfferReceivedNotification extends Notification
{
    use Queueable;

    protected $offer;

    /**
     * Create a new notification instance.
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Simpan notifikasi ke database
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Kontraktor {$this->offer->contractor->name} telah memberikan penawaran terhadap postingan Anda yang berjudul '{$this->offer->post->judul}'.",
            'offer_id' => $this->offer->id,
            'post_id' => $this->offer->post->id,
            'contractor_id' => $this->offer->contractor->id,
            'url' => route('offers.index', $this->offer->post->id), // Link ke halaman penawaran
        ];
    }
}
