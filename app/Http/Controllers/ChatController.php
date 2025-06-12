<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'sender' => 'required|string|max:50',
        ]);
        $message = $request->input('message');
        $sender = $request->input('sender');

        broadcast(new MessageSent($message, $sender))->toOthers();

        return response()->json([
            'status' => 'Message sent!',
            'message' => $message,
            'sender' => $sender,
        ]);
    }
}
