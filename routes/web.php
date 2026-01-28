<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Products\Index as ProductsIndex;
use App\Http\Controllers\ProductController;

use App\Livewire\Admin\Products\Index as AdminProductsIndex;
use App\Livewire\Admin\Products\Create as AdminProductsCreate;
use App\Livewire\Admin\Products\Edit as AdminProductsEdit;

use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

// Public product browsing
Route::get('/products', ProductsIndex::class)->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Admin/Employee product management (RBAC protected)
Route::middleware(['auth', 'verified', 'role:employee,admin'])->group(function () {
    Route::get('/admin/products', AdminProductsIndex::class)->name('admin.products.index');
    Route::get('/admin/products/create', AdminProductsCreate::class)->name('admin.products.create');
    Route::get('/admin/products/{product}/edit', AdminProductsEdit::class)->name('admin.products.edit');
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
