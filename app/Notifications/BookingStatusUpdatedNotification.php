<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $booking;
    protected $status;
    protected $declineReason;
    protected $customMessage;

    public function __construct($booking, $status, $declineReason = null, $customMessage = null)
    {
        $this->booking = $booking;
        $this->status = $status;
        $this->declineReason = $declineReason;
        $this->customMessage = $customMessage; // Untuk pesan kustom seperti pada final approve
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan notifikasi di database
    }

    public function toDatabase($notifiable)
    {
        if ($this->status === 'final_approve') {
            $message = $this->customMessage ?? "User {$this->booking->user->name} telah memberikan final approve untuk pesanan '{$this->booking->judul}'.";
        } else {
            $message = $this->status === 'accepted'
                ? "Pesanan Anda '{$this->booking->judul}' telah diterima oleh kontraktor."
                : "Pesanan Anda '{$this->booking->judul}' telah ditolak oleh kontraktor."
                    . ($this->declineReason ? " Alasan: {$this->declineReason}" : "");
        }

        return [
            'booking_id' => $this->booking->id,
            'judul' => $this->booking->judul,
            'contractor_name' => $this->booking->contractor->name,
            'status' => $this->status,
            'message' => $message,
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
