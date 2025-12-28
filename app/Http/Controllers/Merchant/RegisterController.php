<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        $recommendations = Product::all()->random(5);

        return view('merchant.register', [
            'recommendations' => $recommendations,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=> ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'country' => ['required', 'max:255'],
            'address' => ['required', 'max:255'],
            'notes' => ['required', 'max:255'],
            'postal_code' => ['required', 'numeric', 'digits:5'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        $merchant = Merchant::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        Location::create([
            'city' => $request->city,
            'country' => $request->country,
            'address' => $request->address,
            'notes' => $request->notes,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'locationable_id' => $merchant->id,
            'locationable_type' => 'merchant',
        ]);

        return redirect()->route('home.index');
    }
}
