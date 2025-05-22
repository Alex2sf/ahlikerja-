<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PaymentStageUpdatedNotification extends Notification
{
    use Queueable;

    protected $entity;
    protected $stage;
    protected $type;
    protected $isFinal;

    /**
     * Create a new notification instance.
     *
     * @param mixed $entity Order atau Booking
     * @param int $stage Tahap pembayaran
     * @param string $type Tipe pemesanan ('order' atau 'booking')
     * @param bool $isFinal Apakah ini pembayaran final (selesai)
     */
    public function __construct($entity, $stage, $type, $isFinal = false)
    {
        $this->entity = $entity;
        $this->stage = $stage;
        $this->type = $type;
        $this->isFinal = $isFinal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $title = $this->entity->judul ?? $this->entity->post->judul;
        $message = $this->isFinal
            ? "User {$this->entity->user->name} telah menyelesaikan pembayaran final untuk pesanan '{$title}'."
            : "User {$this->entity->user->name} telah mengunggah bukti pembayaran untuk tahap {$this->stage} pada pesanan '{$title}'.";

        $url = $this->type === 'order'
            ? route('orders.index')
            : route('bookings.index');

        return [
            'message' => $message,
            'url' => $url,
        ];
    }
}
