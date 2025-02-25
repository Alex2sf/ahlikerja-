<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        $chats = [];

        if ($user->role === 'user') {
            // User melihat daftar kontraktor yang pernah dia chat
            $chats = Chat::where('sender_id', $user->id)
                         ->orWhere('receiver_id', $user->id)
                         ->with(['sender', 'receiver'])
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->unique(function ($chat) {
                             return $chat->sender_id === Auth::id() ? $chat->receiver_id : $chat->sender_id;
                         });
        } elseif ($user->role === 'kontraktor') {
            // Kontraktor melihat daftar user yang pernah dia chat
            $chats = Chat::where('sender_id', $user->id)
                         ->orWhere('receiver_id', $user->id)
                         ->with(['sender', 'receiver'])
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->unique(function ($chat) {
                             return $chat->sender_id === Auth::id() ? $chat->receiver_id : $chat->sender_id;
                         });
        }

        return view('chat.index', compact('chats', 'user'));
    }

    public function show($receiverId)
    {
        $sender = Auth::user();
        $receiver = User::findOrFail($receiverId);

        // Pastikan sender adalah user dan receiver adalah kontraktor, atau sebaliknya
        if (!($sender->role === 'user' && $receiver->role === 'kontraktor') &&
            !($sender->role === 'kontraktor' && $receiver->role === 'user')) {
            return redirect()->route('home')->with('error', 'Chat hanya bisa dilakukan antara user dan kontraktor.');
        }

        // Ambil semua chat antara sender dan receiver
        $chats = Chat::where(function ($query) use ($sender, $receiver) {
            $query->where('sender_id', $sender->id)->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($sender, $receiver) {
            $query->where('sender_id', $receiver->id)->where('receiver_id', $sender->id);
        })->with(['sender', 'receiver'])->orderBy('created_at', 'asc')->get();

        // Tandai pesan sebagai sudah dibaca jika belum dibaca
        Chat::where('receiver_id', $sender->id)
            ->where('sender_id', $receiver->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('chats', 'sender', 'receiver'));
    }

    public function store(Request $request, $receiverId)
    {
        $sender = Auth::user();
        $receiver = User::findOrFail($receiverId);

        // Validasi
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Pastikan sender adalah user dan receiver adalah kontraktor, atau sebaliknya
        if (!($sender->role === 'user' && $receiver->role === 'kontraktor') &&
            !($sender->role === 'kontraktor' && $receiver->role === 'user')) {
            return redirect()->back()->with('error', 'Chat hanya bisa dilakukan antara user dan kontraktor.');
        }

        Chat::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
