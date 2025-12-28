<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Following;
use App\Models\Location;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function general_index()
    {
        $recommendations = Product::inRandomOrder()->limit(5)->get();

        return view('profile.general', [
            'recommendations' => $recommendations,
        ]);
    }

    public function general_update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        // 1. Logika Update Username
        if ($request->filled('username')) {
            $request->validate([
                'username' => [
                    'required',
                    'max:255',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],
            ]);
            $user->username = $request->username;
        }

        // 2. Logika Update Tanggal Lahir (Variabel form: dob)
        if ($request->filled('dob')) {
            $request->validate([
                'dob' => ['required', 'date'],
            ]);
            $user->tanggal_lahir = $request->dob;
        }

        // 3. Logika Update Jenis Kelamin (Variabel form: gender)
        if ($request->filled('gender')) {
            $user->jenis_kelamin = $request->gender;
        // 4. Logika Update Telepon (Variabel form: phone)
        if ($request->filled('phone')) {
            $request->validate([
                'phone' => ['required', 'numeric'],
            ]);
            $user->telepon = $request->phone;
        }

        // 5. Logika Update Gambar
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['required', 'image', 'max:10240'],
            ]);

            if ($user->image) {
                // Hapus file lama (asumsi disimpan di public disk dengan prefix 'storage/')
                Storage::disk('public')->delete(str_replace('storage/', '', $user->image));
            }

            $path = $request->file('image')->store('user-images', 'public');
            $user->image = 'storage/' . $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function location_index(Location $request)
    {
        $recommendations = Product::inRandomOrder()->limit(5)->get();

        return view('profile.location', [
            'recommendations' => $recommendations,
        ]);
    }

    public function location_store(Request $request)
    {
        $validated = $request->validate([
            'city' => ['required'],
            'country' => ['required'],
            'address' => ['required'],
            'notes' => ['required'],
            'postal_code' => ['required', 'numeric', 'digits:5'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        Location::create([
            'city' => $validated['city'],
            'country' => $validated['country'],
            'address' => $validated['address'],
            'notes' => $validated['notes'],
            'postal_code' => $validated['postal_code'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'locationable_id' => Auth::id(),
            'locationable_type' => User::class,
        ]);

        return redirect()->back();
    }

    public function location_destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return redirect()->back();
    }

    public function history_index()
    {
        $recommendations = Product::inRandomOrder()->limit(5)->get();

        return view('profile.history', [
            'recommendations' => $recommendations,
        ]);
    }

    public function following_index()
    {
        $recommendations = Product::inRandomOrder()->limit(5)->get();

        return view('profile.following', [
            'recommendations' => $recommendations,
        ]);
    }

    public function following_store(Request $request)
    {
        $validated = $request->validate([
            'merchant_id' => ['required'],
        ]);

        $user = User::findOrFail(Auth::id());
        $user->followings()->create([
            'merchant_id' => $validated['merchant_id'],
        ]);

        return redirect()->back();
    }

    public function following_destroy(Request $request)
    {
        $validated = $request->validate([
            'merchant_id' => ['required'],
        ]);

        $user = User::findOrFail(Auth::id());

        Following::where('merchant_id', $validated['merchant_id'])
            ->where('user_id', $user->id)
            ->delete();

        return redirect()->back();
    }
}
