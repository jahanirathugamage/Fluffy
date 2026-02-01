<?php
// app\Http\Controllers\Api\AuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(\App\Http\Requests\Api\Auth\LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Define scopes based on role
        $abilities = ['basic']; // Default scope
        
        if ($user->hasRole('customer')) {
            $abilities = array_merge($abilities, ['order:create', 'order:view']);
        }
        
        if ($user->hasRole('employee') || $user->hasRole('admin')) {
            $abilities = array_merge($abilities, ['products:manage', 'order:view', 'order:update']);
        }

        // Token creation with device name and scopes
        $deviceName = $request->device_name ?? 'Unknown Device';
        $token = $user->createToken($deviceName, $abilities)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'token' => $token,
                'user'  => $user,
                'scopes' => $abilities
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
