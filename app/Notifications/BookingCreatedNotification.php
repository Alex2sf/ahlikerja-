<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan notifikasi di database
    }

    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'judul' => $this->booking->judul,
            'user_name' => $this->booking->user->name,
            'message' => "User {$this->booking->user->name} telah membuat pesanan baru: {$this->booking->judul}.",
            'url' => route('bookings.index'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
