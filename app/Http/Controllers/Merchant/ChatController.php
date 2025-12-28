<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $recommendations = Product::all()->random(5);
        $sender = Auth::user()->merchant;
        $chat_type = 'merchant';

        return view('chats.index', [
            'recommendations' => $recommendations,
            'sender' => $sender,
            'chat_type' => $chat_type,
        ]);
    }

    public function show($room_id)
    {
        $room = Room::findOrFail($room_id);
        $recommendations = Product::all()->random(5);
        $sender = Auth::user()->merchant;
        $chat_type = 'merchant';

        return view('chats.show', [
            'recommendations' => $recommendations,
            'room' => $room,
            'sender' => $sender,
            'chat_type' => $chat_type,
        ]);
    }

    public function store($room_id, Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'max:255'],
        ]);

        $room = Room::findOrFail($room_id);
        $room->messages()->create([
            'message' => $request->message,
            'messageable_id' => Auth::user()->merchant->id,
            'messageable_type' => 'merchant',
        ]);

        return redirect()->back();
    }
}
