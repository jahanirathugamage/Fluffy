<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('landing');
});

Route::view('/landing', 'landing')->name('landing');
Route::get('/home', function() { return redirect()->route('landing'); })->name('home'); 

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',

])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('employee')) {
            return redirect()->route('employee.manage-products');
        }
        return redirect()->route('landing');
    })->name('dashboard');
});

// Employee Routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/manage-products', \App\Livewire\Employee\ManageProducts::class)->name('manage-products');
    Route::get('/orders', \App\Livewire\Employee\ManageOrders::class)->name('orders');
    Route::get('/orders/{order}', \App\Livewire\Employee\ManageOrderDetails::class)->name('order-details');
    Route::get('/create-account', \App\Livewire\Employee\CreateAccount::class)->name('create-account');
});

// Customer Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', \App\Livewire\Customer\MyOrders::class)->name('my-orders.index');
    Route::get('/my-orders/{order}', \App\Livewire\Customer\MyOrderDetails::class)->name('my-orders.show');
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
});

// Product Routes
Route::get('/products', \App\Livewire\Products\Index::class)->name('products.index');
// Use the ProductDetail Livewire component as the full-page controller
Route::get('/products/{product}', \App\Livewire\Products\ProductDetail::class)->name('products.show');



// Google Auth
Route::get('/auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback'])->name('google.callback');

// Jetstream Profile Route (Override if needed, but default is fine usually)
// Route::middleware(['auth'])->get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');
