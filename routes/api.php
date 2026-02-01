<?php
// routes\api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Routes
    |--------------------------------------------------------------------------
    */
    
    // Auth
    Route::post('/login', [AuthController::class, 'login']);

    // Products (public)
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{product}', [ProductApiController::class, 'show']);

    // Stripe Webhook (Public, secured by signature)
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Sanctum)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);

        // Logged-in user info
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Orders (customer)
        // Check scope 'order:view' or 'order:create'
        Route::middleware('ability:order:view')->get('/orders', [OrderApiController::class, 'index']);
        Route::middleware('ability:order:create')->post('/orders', [OrderApiController::class, 'store']);

        /*
        |--------------------------------------------------------------------------
        | Admin / Employee API (RBAC)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['role:admin,employee', 'ability:products:manage'])->group(function () {
             // future admin APIs
        });
    });
});

/*
|--------------------------------------------------------------------------
| Web Session Payment Routes (Legacy / Web Frontend)
|--------------------------------------------------------------------------
| These routes keep using web session for the livewire/blade frontend.
| We don't version these as they are internal to the frontend app.
*/
Route::middleware(['web', 'auth'])->prefix('payment')->group(function () {
    Route::post('/create-intent', [\App\Http\Controllers\Api\PaymentController::class, 'createPaymentIntent']);
    Route::post('/confirm', [\App\Http\Controllers\Api\PaymentController::class, 'confirmPayment']);
});
