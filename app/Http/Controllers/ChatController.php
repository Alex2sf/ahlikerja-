<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{
    public function index($receiverId = null)
    {
        $user = Auth::user();
        $chats = Chat::where(function ($query) use ($user) {
                            $query->where('sender_id', $user->id)
                                  ->orWhere('receiver_id', $user->id);
                        })
                        ->with(['sender', 'receiver'])
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->unique(function ($chat) use ($user) {
                            return $chat->sender_id === $user->id ? $chat->receiver_id : $chat->sender_id;
                        });

        $receiver = $receiverId ? User::findOrFail($receiverId) : null;
        $selectedChats = collect();

        if ($receiver) {
            $selectedChats = Chat::where(function ($query) use ($user, $receiver) {
                                        $query->where('sender_id', $user->id)
                                              ->where('receiver_id', $receiver->id)
                                              ->orWhere('sender_id', $receiver->id)
                                              ->where('receiver_id', $user->id);
                                    })
                                    ->orderBy('created_at', 'asc')
                                    ->get();

            // Tandai semua pesan dari receiver sebagai sudah dibaca
            Chat::where('receiver_id', $user->id)
                ->where('sender_id', $receiver->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.index', compact('chats', 'receiver', 'selectedChats'));
    }

    public function store(Request $request, $receiverId)
    {
        if (!ProfileController::isProfileComplete(Auth::user())) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $sender = Auth::user();
        $receiver = User::findOrFail($receiverId);

        if ($sender->role === 'kontraktor' && (!$sender->contractorProfile || !$sender->contractorProfile->approved)) {
            return redirect()->back()->with('error', 'Anda harus disetujui oleh admin terlebih dahulu untuk mengirim chat.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
            'attachment' => 'nullable|file|max:2048'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $fileName = time() . '_' . uniqid() . '.' . $attachment->extension();
            $attachmentPath = $attachment->storeAs('chats', $fileName, 'public');
        }

        $chat = Chat::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_read' => false
        ]);

        $receiver->notify(new NewMessageNotification($chat));

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
