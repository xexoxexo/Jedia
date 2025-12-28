<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $recommendations = Product::all()->random(5);
        $sender = Auth::user();
        $chat_type = 'user';

        return view('chats.index', [
            'recommendations' => $recommendations,
            'sender' => $sender,
            'chat_type' => $chat_type,
        ]);
    }

    public function redirect($merchant_id)
    {
        $room = Room::whereHas('roomables', function (Builder $query) {
            $query->where('roomable_id', Auth::user()->id)
            ->where('roomable_type','user');
        })->whereHas('roomables', function (Builder $query) use ($merchant_id) {
            $query->where('roomable_id', $merchant_id)
            ->where('roomable_type', 'merchant');
        })->first();

        if ($room == null) {
            $room = Room::create();
            $room->roomables()->create([
                'roomable_id' => Auth::user()->id,
                'roomable_type' => 'user'
            ]);
            $room->roomables()->create([
                'roomable_id' => $merchant_id,
                'roomable_type' => 'merchant'
            ]);
        }

        return redirect()->route('chats.show', ['room_id' => $room->id]);

    }

    public function show($room_id)
    {
        $room = Room::findOrFail($room_id);
        $recommendations = Product::all()->random(5);
        $sender = Auth::user();
        $chat_type = 'user';

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
            'messageable_id' => Auth::user()->id,
            'messageable_type' => 'user',
        ]);

        return redirect()->back();
    }
}
