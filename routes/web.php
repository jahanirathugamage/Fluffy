<?php
// routes\web.php
use Illuminate\Support\Facades\Route;

use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Employee\ManageProducts;

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CheckoutController;

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

// Customer-only product browsing and checkout
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/products', ProductsIndex::class)->name('products.index');
    Route::get('/products/{product}', \App\Livewire\Products\ProductDetail::class)->name('products.show');
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Employee product management
Route::middleware(['auth', 'verified', 'permission:view-dashboard'])->group(function () {
    Route::get('/employee/manage-products', ManageProducts::class)->name('employee.manage-products');
    
    // Order Management
    Route::middleware(['permission:manage-orders'])->group(function () {
        Route::get('/employee/orders', \App\Livewire\Employee\ManageOrders::class)->name('employee.orders.index');
        Route::get('/employee/orders/{order}', \App\Livewire\Employee\ManageOrderDetails::class)->name('employee.orders.show');
    });
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

    // My Orders
    Route::get('/my-orders', \App\Livewire\Customer\MyOrders::class)->name('my-orders.index');
    Route::get('/my-orders/{order}', \App\Livewire\Customer\MyOrderDetails::class)->name('my-orders.show');
});
