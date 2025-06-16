<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ContractorApprovalNotification extends Notification
{
    use Queueable;

    protected $approved;
    protected $adminNote;

    /**
     * Create a new notification instance.
     *
     * @param bool $approved
     * @param string|null $adminNote
     */
    public function __construct(bool $approved, ?string $adminNote)
    {
        $this->approved = $approved;
        $this->adminNote = $adminNote;
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
     * Get the array representation of the notification for database.
     *
     * @param mixed $notifiable
     * @return DatabaseMessage
     */
    public function toDatabase($notifiable)
    {
        if ($this->approved) {
            $message = 'Kamu telah diterima dan boleh mengakses semua fitur.';
            $url = route('contractor.profile.show'); // Arahkan ke profil kontraktor
        } else {
            $message = 'Kamu telah ditolak' . ($this->adminNote ? ' dengan alasan: ' . $this->adminNote : '.');
            $url = route('contractor.profile.show'); // Arahkan ke profil untuk melihat catatan
        }

        return [
            'message' => $message,
            'url' => $url,
        ];
    }
}
