<?php
// app\Http\Responses\LoginResponse.php
namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // If employee (or has employee capability), go to manage-products first
        if ($user && ($user->hasRole('employee') || $user->can('view-dashboard'))) {
            return redirect()->intended(route('employee.manage-products'));
        }

        // Everyone else (customer) goes to landing
        return redirect()->intended(route('landing'));
    }
}
