<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $socialUser = Socialite::driver('google')->user();
        $googleUser = User::where('google_id', $socialUser->getId())->first();

        if ($googleUser) {
            Auth::login($googleUser);

            return redirect()->route('home.index');
        } else {
            $emailUser = User::where('email', $socialUser->getEmail())->first();
            if ($emailUser) {
                return redirect()->route('register.index')->withInput([
                    'email' => $socialUser->getEmail(),
                ]);
            } else {
                $nameUser = User::where('username', $socialUser->getName())->first();

                if ($nameUser) {
                    return redirect()->route('register.index')->withInput([
                        'username' => $socialUser->getName(),
                    ]);
                } else {
                    $user = User::create([
                        'username' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'google_id' => $socialUser->getId(),
                    ]);

                    Auth::login($user);

                    return redirect()->route('home.index');
                }
            }
        }
    }
}
