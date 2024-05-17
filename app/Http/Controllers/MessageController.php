<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'channel_id' => $request->channel_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => $message, 'channel_id' => $request->channel_id], 201);
    }
    
    public function getMessages($channel_id)
    {
        $messages = Message::where('channel_id', $channel_id)->get();
        return response()->json($messages);
    }
}
