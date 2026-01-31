<?php
// app/Http/Controllers/Auth/GoogleController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Permission;

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
                'password' => bcrypt(Str::random(32)),
            ]
        );

        /**
         * Ensure role/permission exists for Google-created users
         * (prevents 403 on routes protected by `permission:buy-products`)
         */
        if ($user->roles()->count() === 0) {
            $user->assignRole('customer');
        }

        // If your system protects customer pages with `permission:buy-products`,
        // make sure the user has it (only if the permission exists).
        if (Permission::where('name', 'buy-products')->exists() && !$user->can('buy-products')) {
            $user->givePermissionTo('buy-products');
        }

        Auth::login($user, true);

        if (request()->wantsJson()) {
            $user->tokens()->delete();

            $token = $user->createToken('google-oauth-token')->plainTextToken;

            return response()->json([
                'message' => 'Logged in with Google',
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Redirect based on role
        if ($user->hasRole('employee') || $user->can('view-dashboard')) {
            return redirect()->route('employee.manage-products');
        }

        return redirect()->route('landing');
    }
}
