<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $channel = Channel::create([
            'name' => $validatedData['name'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['channel' => $channel], 201);
    }

    public function index()
    {
        $channels = Channel::all();
        return response()->json($channels);
    }
}
