<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'google_id' => $googleUser->getId(),
                // Set a random password so DB constraint is satisfied (user won’t use it)
                'password' => bcrypt(Str::random(32)),
            ]
        );

        Auth::login($user, true);

        /**
         * ✅ If request expects JSON (API style):
         * Return a Sanctum token so you can test protected API endpoints.
         */
        if (request()->wantsJson()) {
            // Optional: delete old tokens so user only has one active token
            $user->tokens()->delete();

            $token = $user->createToken('google-oauth-token')->plainTextToken;

            return response()->json([
                'message' => 'Logged in with Google',
                'token' => $token,
                'user' => $user,
            ]);
        }

        /**
         * ✅ Normal web flow:
         * Redirect to dashboard
         */
        return redirect()->route('dashboard');
    }
}
