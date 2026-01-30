<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Products\Index as ProductsIndex;
use App\Http\Controllers\ProductController;

use App\Livewire\Employee\ManageProducts;

use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('landing');
    }
    return redirect()->route('login');
})->name('home');

// Public product browsing
Route::get('/products', ProductsIndex::class)->name('products.index');
Route::get('/products/{product}', \App\Livewire\Products\ProductDetail::class)->name('products.show');

// Employee product management - using permission-based access control (single page)
Route::middleware(['auth', 'verified', 'permission:view-dashboard'])->group(function () {
    Route::get('/employee/manage-products', ManageProducts::class)->name('employee.manage-products');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::get('/auth/google/token', function () {
    abort_unless(auth()->check(), 401);

    $user = auth()->user();
    $user->tokens()->delete();

    return response()->json([
        'token' => $user->createToken('web-issued-token')->plainTextToken,
        'user' => $user,
    ]);
})->middleware('auth');

// Customer routes - using permission-based access control
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'permission:buy-products', // Permission-based instead of role-based
])->group(function () {
    Route::view('/landing', 'landing')->name('landing');
    Route::view('/membership', 'membership')->name('membership');
    
    // Animal category routes - redirect to products page with filter
    Route::get('/cats', function () {
        return redirect()->route('products.index', ['animal' => 'cat']);
    })->name('cats');
    
    Route::get('/dogs', function () {
        return redirect()->route('products.index', ['animal' => 'dog']);
    })->name('dogs');
    
    Route::get('/rabbits', function () {
        return redirect()->route('products.index', ['animal' => 'rabbit']);
    })->name('rabbits');
    
    Route::get('/hamsters', function () {
        return redirect()->route('products.index', ['animal' => 'hamster']);
    })->name('hamsters');
    
    Route::get('/seasonal', function () {
        return redirect()->route('products.index', ['category' => 'seasonal']);
    })->name('seasonal');

    Route::get('/favorites', \App\Livewire\Favorites\Index::class)->name('favorites.index');
    
    // Cart routes
    Route::get('/cart', \App\Livewire\Cart\CartDrawer::class)->name('cart.index');
    
    // Profile route
    Route::view('/profile', 'profile.show')->name('profile');
    
    // Orders route
    Route::view('/orders', 'orders.index')->name('orders.index');
});
