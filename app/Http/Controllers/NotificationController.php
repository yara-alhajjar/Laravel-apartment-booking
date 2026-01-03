<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->notifications()->orderBy('created_at', 'desc')->get()
        );
    }

    public function unread()
    {
        return response()->json(
            Auth::user()->unreadNotifications()->orderBy('created_at', 'desc')->get()
        );
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->each->markAsRead();
        return response()->json(['message' => 'All notifications marked as read']);
    }

}
