<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /** Redirect to Google for authentication */
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /** Handle callback from Google */
    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Try to find existing user by provider id
        $user = User::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if (!$user) {
            // If email exists, attach provider info, otherwise create
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
                    'email' => $googleUser->getEmail(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                ]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
