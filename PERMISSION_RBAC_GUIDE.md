# Permission-Based RBAC Implementation Guide

Complete guide for using permission-based access control with Spatie Laravel Permission in the Fluffy application.

---

## 📋 Table of Contents
- [Overview](#overview)
- [Permissions & Roles](#permissions--roles)
- [Route Protection](#route-protection)
- [Livewire Component Protection](#livewire-component-protection)
- [Blade Directives](#blade-directives)
- [Why Permissions > Hard-Coded Roles](#why-permissions--hard-coded-roles)

---

## Overview

The Fluffy application now uses **permission-based RBAC** on top of roles for flexible, granular access control.

### Permissions Created

| Permission | Description | Assigned To |
|-----------|-------------|-------------|
| `view-products` | View product listings | Customer, Employee |
| `buy-products` | Purchase products | Customer |
| `manage-products` | Create/edit/delete products | (Future) |
| `manage-orders` | View and manage orders | Employee |
| `manage-users` | Manage user accounts | (Future) |
| `view-dashboard` | Access employee dashboard | Employee |

### Role-Permission Mapping

| Role | Permissions |
|------|------------|
| **Customer** | `view-products`, `buy-products` |
| **Employee** | `view-products`, `manage-orders`, `view-dashboard` |

---

## Route Protection

### Customer Routes (Permission-Based)

```php
// routes/web.php

// Using permission middleware instead of role middleware
Route::middleware(['auth', 'verified', 'permission:buy-products'])->group(function () {
    Route::view('/landing', 'landing')->name('landing');
    Route::view('/membership', 'membership')->name('membership');
    
    // Shopping cart (requires buy-products permission)
    Route::get('/cart', CartIndex::class)->name('cart.index');
});
```

**Why this is better:**
- ✅ Can add "Guest Customer" role with only `view-products` (no buying)
- ✅ Can add "Premium Customer" with special permissions
- ✅ Permission name is self-documenting

### Employee Routes (Permission-Based)

```php
// Employee dashboard - requires view-dashboard permission
Route::middleware(['auth', 'verified', 'permission:view-dashboard'])->group(function () {
    Route::get('/admin/products', AdminProductsIndex::class)->name('admin.products.index');
    Route::get('/admin/products/create', AdminProductsCreate::class)->name('admin.products.create');
    Route::get('/admin/products/{product}/edit', AdminProductsEdit::class)->name('admin.products.edit');
});

// Order management - requires manage-orders permission
Route::middleware(['auth', 'verified', 'permission:manage-orders'])->group(function () {
    Route::get('/admin/orders', OrdersIndex::class)->name('admin.orders.index');
    Route::get('/admin/orders/{order}', OrderShow::class)->name('admin.orders.show');
});
```

### Multiple Permissions (OR Logic)

```php
// Allow users with EITHER permission
Route::middleware(['auth', 'permission:view-dashboard|manage-products'])->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
});
```

### Multiple Permissions (AND Logic)

```php
// Require user to have ALL permissions
Route::middleware(['auth', 'permission:manage-products,manage-users'])->group(function () {
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});
```

---

## Livewire Component Protection

### Method 1: abort_unless in mount()

```php
<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;

class Create extends Component
{
    public function mount()
    {
        // Simple and clean - returns 403 if permission missing
        abort_unless(auth()->user()->can('manage-products'), 403);
    }

    public function render()
    {
        return view('livewire.admin.products.create');
    }
}
```

### Method 2: Using authorize() Helper

```php
<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use App\Models\Product;

class Edit extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function save()
    {
        // Throws 403 if user doesn't have permission
        $this->authorize('manage-products');

        $this->product->save();
        session()->flash('success', 'Product updated!');
    }

    public function render()
    {
        return view('livewire.admin.products.edit');
    }
}
```

### Method 3: Conditional Logic with hasPermissionTo()

```php
<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class Index extends Component
{
    public function addToCart($productId)
    {
        // Check permission before allowing action
        if (!auth()->user()->hasPermissionTo('buy-products')) {
            session()->flash('error', 'You need to be a customer to purchase products');
            return redirect()->route('register');
        }

        // Add to cart logic here
        session()->flash('success', 'Added to cart!');
    }

    public function deleteProduct($productId)
    {
        // Different permission for deletion
        if (!auth()->user()->hasPermissionTo('manage-products')) {
            session()->flash('error', 'Unauthorized action');
            return;
        }

        Product::destroy($productId);
        session()->flash('success', 'Product deleted');
    }

    public function render()
    {
        return view('livewire.products.index', [
            'products' => Product::all(),
        ]);
    }
}
```

### Method 4: Conditional Rendering in Component

```php
<?php

namespace App\Livewire\Fluffy;

use Livewire\Component;

class Navbar extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        return view('livewire.fluffy.navbar', [
            'canBuyProducts' => $user && $user->can('buy-products'),
            'canManageProducts' => $user && $user->can('manage-products'),
            'canViewDashboard' => $user && $user->can('view-dashboard'),
        ]);
    }
}
```

Then in blade:
```blade
@if($canViewDashboard)
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endif

@if($canManageProducts)
    <a href="{{ route('admin.products.index') }}">Manage Products</a>
@endif
```

---

## Blade Directives

### @can Directive (Recommended)

```blade
{{-- Show "Add to Cart" only if user can buy products --}}
@can('buy-products')
    <button wire:click="addToCart({{ $product->id }})" class="btn-primary">
        Add to Cart
    </button>
@endcan

{{-- Show admin tools for product management --}}
@can('manage-products')
    <div class="admin-toolbar">
        <a href="{{ route('admin.products.create') }}">Add Product</a>
        <a href="{{ route('admin.products.index') }}">Manage Inventory</a>
    </div>
@endcan

{{-- Dashboard link --}}
@can('view-dashboard')
    <a href="{{ route('dashboard') }}" class="nav-link">
        Employee Dashboard
    </a>
@endcan
```

### @cannot Directive

```blade
{{-- Show message if user lacks permission --}}
@cannot('buy-products')
    <div class="alert alert-info">
        <p>Please <a href="{{ route('register') }}">register as a customer</a> to purchase products.</p>
    </div>
@endcannot

@cannot('manage-products')
    <p class="text-gray-500">Product management is for employees only.</p>
@endcannot
```

### @canany Directive (Multiple Permissions)

```blade
{{-- Show if user has ANY of these permissions --}}
@canany(['manage-products', 'manage-orders', 'view-dashboard'])
    <div class="admin-section">
        <h2>Admin Tools</h2>
        
        @can('manage-products')
            <a href="{{ route('admin.products.index') }}">Products</a>
        @endcan
        
        @can('manage-orders')
            <a href="{{ route('admin.orders.index') }}">Orders</a>
        @endcan
    </div>
@endcanany
```

### Combined @role and @can

```blade
{{-- Check role first, then permission --}}
@role('employee')
    @can('view-dashboard')
        <a href="{{ route('dashboard') }}">Dashboard</a>
    @endcan
    
    @can('manage-orders')
        <a href="{{ route('admin.orders.index') }}">Manage Orders</a>
    @endcan
@endrole

@role('customer')
    @can('buy-products')
        <button>Checkout</button>
    @endcan
@endrole
```

### Show/Hide Navigation Items

```blade
{{-- Navbar example --}}
<nav class="navbar">
    <a href="{{ route('home') }}">Home</a>
    
    @can('view-products')
        <a href="{{ route('products.index') }}">Products</a>
    @endcan
    
    @can('buy-products')
        <a href="{{ route('cart.index') }}">
            Cart
            <span class="badge">{{ $cartCount }}</span>
        </a>
    @endcan
    
    @can('view-dashboard')
        <a href="{{ route('dashboard') }}">Admin Dashboard</a>
    @endcan
    
    @can('manage-orders')
        <a href="{{ route('admin.orders.index') }}">Orders</a>
    @endcan
</nav>
```

### Conditional Buttons in Tables

```blade
{{-- Product listing --}}
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ $product->price }}</td>
                <td>
                    @can('buy-products')
                        <button wire:click="addToCart({{ $product->id }})">
                            Add to Cart
                        </button>
                    @endcan
                    
                    @can('manage-products')
                        <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
                        <button wire:click="delete({{ $product->id }})" class="text-red-600">
                            Delete
                        </button>
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

---

## Why Permissions > Hard-Coded Roles

### ❌ Old Way: Hard-Coded Role Checks

```php
// Controller
if ($user->hasRole('employee')) {
    // Show admin menu
}

// Blade
@role('employee')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endrole
```

**Problems:**
1. Adding "Manager" or "Senior Employee" requires code changes everywhere
2. Can't give an individual employee special access without changing their role
3. Role names tied to business logic ("employee" might not make sense later)
4. Hard to audit "who can delete products?"
5. Can't easily add role hierarchies

### ✅ New Way: Permission-Based Checks

```php
// Controller
if ($user->can('view-dashboard')) {
    // Show admin menu
}

// Blade
@can('view-dashboard')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endcan
```

**Benefits:**
1. ✅ Add "Manager" role with different permissions - no code changes!
2. ✅ Give specific users extra permissions without role changes
3. ✅ Permission names describe capabilities, not job titles
4. ✅ Easy to audit: "Who can `manage-products`?"
5. ✅ Future-proof for role hierarchies
6. ✅ Can add/remove permissions from roles in database
7. ✅ Self-documenting code (`can('manage-products')` is clear)

### Real-World Example

**Scenario:** You want to add a "Premium Customer" role

**With Hard-Coded Roles (❌ Requires Code Changes):**
```php
// Have to update everywhere:
@role('customer|premium-customer')  // Changed
@role('employee')
```

**With Permissions (✅ No Code Changes):**
```php
// Just add permissions to new role in database:
DB::table('role_has_permissions')->insert([
    'role_id' => $premiumCustomerRole->id,
    'permission_id' => Permission::where('name', 'view-products')->first()->id,
]);

// All your @can('view-products') directives work automatically!
```

### Flexibility Example

```php
// Give a specific customer special permission
$specialCustomer = User::find(123);
$specialCustomer->givePermissionTo('manage-orders'); // Customer can now see orders!

// This works without changing their "customer" role
// and without any code changes
```

---

## Quick Reference

### Check Permission in Code
```php
// Check single permission
auth()->user()->can('manage-products')
auth()->user()->hasPermissionTo('manage-products')

// Check any permission
auth()->user()->hasAnyPermission(['manage-products', 'manage-orders'])

// Check all permissions
auth()->user()->hasAllPermissions(['view-products', 'buy-products'])
```

### Grant/Revoke Permissions
```php
// Give permission directly to user
$user->givePermissionTo('manage-products');

// Remove permission from user
$user->revokePermissionTo('manage-products');

// Sync permissions for a role
$role->syncPermissions(['view-products', 'buy-products']);
```

### Common Patterns

```php
// In controller
if (! Auth::user()->can('manage-products')) {
    abort(403, 'Unauthorized');
}

// In Livewire
abort_unless(auth()->user()->can('manage-products'), 403);

// In Blade
@can('manage-products')
    <button>Delete</button>
@endcan
```

---

## Summary

✅ **Permission-based RBAC is now fully implemented:**
- 6 permissions created and synced to roles
- Routes use `permission:` middleware
- Livewire components protected with `can()` checks
- Blade directives use `@can` for conditional rendering
- Much more flexible than hard-coded role checks
- Future-proof for role hierarchies and special permissions

🎉 **Your application is now scalable and maintainable!**
