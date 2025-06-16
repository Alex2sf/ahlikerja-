<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class PostDeletedByAdminNotification extends Notification
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Notifikasi disimpan di database untuk ditampilkan di UI
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Postingan Anda dengan judul '{$this->post->judul}' telah dihapus oleh admin karena tidak sesuai dengan panduan yang berlaku. Silakan buat ulang.",
            'url' => route('posts.create'), // URL untuk kembali ke halaman buat postingan
        ];
    }
}
