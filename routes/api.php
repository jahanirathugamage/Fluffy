<?php
// routes\api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
| These routes do NOT require authentication
*/

// Auth
Route::post('/login', [AuthController::class, 'login']);

// Products (public)
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{product}', [ProductApiController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum)
|--------------------------------------------------------------------------
| These routes REQUIRE a valid Sanctum token
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Logged-in user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Orders (customer)
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::post('/orders', [OrderApiController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | Admin / Employee API (RBAC)
    |--------------------------------------------------------------------------
    | API role protection — NOT web middleware
    */
    Route::middleware('role:admin,employee')->group(function () {
        // future admin APIs (example)
        // Route::post('/products', ...);
        // Route::put('/products/{id}', ...);
        // Route::delete('/products/{id}', ...);
    });
});
