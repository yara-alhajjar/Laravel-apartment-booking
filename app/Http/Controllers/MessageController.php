<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'apartment_id' => 'required|exists:apartments,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id'    => Auth::id(),
            'receiver_id'  => $request->receiver_id,
            'apartment_id' => $request->apartment_id,
            'content'      => $request->content,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data'    => $message,
        ], 201);
    }

    public function apartmentMessages($apartmentId)
    {
        $messages = Message::with(['sender', 'receiver'])
            ->where('apartment_id', $apartmentId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function myMessages()
{
    $messages = Message::with(['sender', 'receiver', 'apartment'])
        ->where(function ($query) {
            $query->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($messages);
}

}
