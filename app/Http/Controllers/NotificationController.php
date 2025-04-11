<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        // Cari notifikasi berdasarkan ID dan pastikan milik user yang sedang login
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', \App\Models\User::class)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // Ambil semua notifikasi yang belum dibaca untuk user yang sedang login
        $unreadNotifications = DatabaseNotification::where('notifiable_id', Auth::id())
            ->where('notifiable_type', \App\Models\User::class)
            ->whereNull('read_at')
            ->get();

        // Tandai semua sebagai dibaca
        foreach ($unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
