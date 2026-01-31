<?php
// routes\web.php
use Illuminate\Support\Facades\Route;

use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Employee\ManageProducts;

use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Logged in: route based on role
    if (auth()->user()->hasRole('employee')) {
        return redirect()->route('employee.manage-products');
    }

    return redirect()->route('landing');
})->name('home');

// Public product browsing
Route::get('/products', ProductsIndex::class)->name('products.index');
Route::get('/products/{product}', \App\Livewire\Products\ProductDetail::class)->name('products.show');

// Employee product management
Route::middleware(['auth', 'verified', 'permission:view-dashboard'])->group(function () {
    Route::get('/employee/manage-products', ManageProducts::class)->name('employee.manage-products');
    Route::get('/employee/orders', \App\Livewire\Employee\Orders::class)->name('admin.orders.index');
});

// Jetstream dashboard route (make it role-smart)
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

// Customer routes (permission-based)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'permission:buy-products',
])->group(function () {
    Route::view('/landing', 'landing')->name('landing');
    Route::view('/membership', 'membership')->name('membership');

    Route::get('/cats', fn () => redirect()->route('products.index', ['animal' => 'cat']))->name('cats');
    Route::get('/dogs', fn () => redirect()->route('products.index', ['animal' => 'dog']))->name('dogs');
    Route::get('/rabbits', fn () => redirect()->route('products.index', ['animal' => 'rabbit']))->name('rabbits');
    Route::get('/hamsters', fn () => redirect()->route('products.index', ['animal' => 'hamster']))->name('hamsters');

    Route::get('/seasonal', fn () => redirect()->route('products.index', ['category' => 'seasonal']))->name('seasonal');

    Route::get('/favorites', \App\Livewire\Favorites\Index::class)->name('favorites.index');

    Route::get('/cart', \App\Livewire\Cart\CartDrawer::class)->name('cart.index');

    Route::view('/profile', 'profile.show')->name('profile');

    Route::view('/orders', 'orders.index')->name('orders.index');
});
