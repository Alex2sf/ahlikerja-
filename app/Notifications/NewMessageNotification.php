<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Chat;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $sender = $this->chat->sender;
        $messagePrefix = $sender->role === 'user' ? 'User' : 'Kontraktor';
        $message = "$messagePrefix {$sender->name} telah memberikan pesan.";

        return [
            'message' => $message,
            'chat_id' => $this->chat->id,
            'sender_id' => $sender->id,
            'url' => route('chats.index', $sender->id),
        ];
    }
}
