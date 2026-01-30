# Spatie RBAC Examples

This document provides practical examples of using Spatie Laravel Permission for role-based access control in the Fluffy application.

## Table of Contents
- [Blade Directives](#blade-directives)
- [Livewire Component Role Checks](#livewire-component-role-checks)
- [Route Protection](#route-protection)
- [Direct Role Checks in Controllers](#direct-role-checks-in-controllers)

---

## Blade Directives

### Show Content to Customers Only
```blade
@role('customer')
    <div class="customer-only-banner">
        <h2>Welcome, valued customer!</h2>
        <p>Check out our exclusive member deals</p>
        <a href="{{ route('membership') }}">View Membership Benefits</a>
    </div>
@endrole
```

### Show Content to Employees Only
```blade
@role('employee')
    <div class="admin-toolbar">
        <a href="{{ route('admin.products.index') }}">Manage Products</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </div>
@endrole
```

### Show Content to Any Authenticated User
```blade
@hasanyrole('customer|employee')
    <p>You are logged in as: {{ auth()->user()->name }}</p>
    <p>Your role: {{ auth()->user()->getRoleNames()->first() }}</p>
@endhasanyrole
```

### Alternative Syntax with @hasrole
```blade
{{-- Same as @role but more explicit --}}
@hasrole('customer')
    <div class="customer-dashboard">
        <h3>My Orders</h3>
        <h3>My Wishlist</h3>
    </div>
@endhasrole
```

### Show Different Content Based on Role
```blade
@role('employee')
    <nav>
        <a href="{{ route('admin.products.create') }}">Add Product</a>
        <a href="{{ route('admin.products.index') }}">Manage Inventory</a>
    </nav>
@elserole('customer')
    <nav>
        <a href="{{ route('products.index') }}">Shop Products</a>
        <a href="{{ route('landing') }}">Home</a>
    </nav>
@endrole
```

---

## Livewire Component Role Checks

### Protect Component Mount Method
```php
<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;

class Create extends Component
{
    public function mount()
    {
        // Ensure only employees can access this component
        if (!auth()->user()->hasRole('employee')) {
            abort(403, 'Unauthorized - Employees only');
        }
    }

    public function render()
    {
        return view('livewire.admin.products.create');
    }
}
```

### Role-Based Method Access
```php
<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class Index extends Component
{
    public function addToWishlist($productId)
    {
        // Only customers can add to wishlist
        if (!auth()->user()->hasRole('customer')) {
            session()->flash('error', 'Only customers can add items to wishlist');
            return;
        }

        // Add to wishlist logic here
        session()->flash('success', 'Added to wishlist!');
    }

    public function deleteProduct($productId)
    {
        // Only employees can delete products
        if (!auth()->user()->hasRole('employee')) {
            session()->flash('error', 'Unauthorized action');
            return;
        }

        Product::destroy($productId);
        session()->flash('success', 'Product deleted');
    }

    public function render()
    {
        return view('livewire.products.index');
    }
}
```

### Conditional Rendering in Livewire
```php
<?php

namespace App\Livewire\Landing;

use Livewire\Component;

class Navbar extends Component
{
    public function render()
    {
        $user = auth()->user();
        $isCustomer = $user && $user->hasRole('customer');
        $isEmployee = $user && $user->hasRole('employee');

        return view('livewire.landing.navbar', [
            'isCustomer' => $isCustomer,
            'isEmployee' => $isEmployee,
        ]);
    }
}
```

Then in the blade file:
```blade
@if($isCustomer)
    <a href="{{ route('landing') }}">My Dashboard</a>
@endif

@if($isEmployee)
    <a href="{{ route('admin.products.index') }}">Admin Panel</a>
@endif
```

---

## Route Protection

### Already Configured in web.php
```php
// Customer-only routes
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::view('/landing', 'landing')->name('landing');
    Route::view('/membership', 'membership')->name('membership');
    
    // Animal category routes
    Route::get('/cats', function () {
        return redirect()->route('products.index', ['animal' => 'cat']);
    })->name('cats');
});

// Employee-only routes
Route::middleware(['auth', 'verified', 'role:employee'])->group(function () {
    Route::get('/admin/products', AdminProductsIndex::class)->name('admin.products.index');
    Route::get('/admin/products/create', AdminProductsCreate::class)->name('admin.products.create');
    Route::get('/admin/products/{product}/edit', AdminProductsEdit::class)->name('admin.products.edit');
});
```

### Multiple Roles (OR Logic)
```php
// Allow both customers AND employees
Route::middleware(['auth', 'role:customer|employee'])->group(function () {
    Route::get('/products', ProductsIndex::class)->name('products.index');
});
```

---

## Direct Role Checks in Controllers

### Using hasRole()
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        // Employees see additional info
        if (auth()->user()->hasRole('employee')) {
            return view('products.show-admin', compact('product'));
        }
        
        // Customers see regular view
        return view('products.show', compact('product'));
    }
}
```

### Using hasAnyRole()
```php
// Check if user has ANY of the specified roles
if (auth()->user()->hasAnyRole(['customer', 'employee'])) {
    // User has at least one of these roles
}
```

### Using hasAllRoles()
```php
// Check if user has ALL specified roles (if you ever assign multiple roles)
if (auth()->user()->hasAllRoles(['customer', 'premium'])) {
    // User has both roles
}
```

---

## Useful Helper Methods

```php
// Get all roles for a user
$roles = auth()->user()->getRoleNames(); // Returns collection: ['customer']

// Get first role name
$roleName = auth()->user()->getRoleNames()->first(); // 'customer'

// Check role (multiple ways)
auth()->user()->hasRole('customer');
auth()->user()->hasRole(['customer', 'employee']); // Has any
auth()->check() && auth()->user()->hasRole('customer');

// Assign role (already handled in CreateNewUser)
$user->assignRole('customer');

// Remove role
$user->removeRole('customer');

// Sync roles (replace all with new set)
$user->syncRoles(['employee']);
```

---

## Common Patterns in Fluffy App

### Navbar - Show Profile vs Dashboard
```blade
@auth
    @role('customer')
        <a href="{{ route('profile.show') }}">My Profile</a>
        <a href="{{ route('landing') }}">My Dashboard</a>
    @endrole
    
    @role('employee')
        <a href="{{ route('dashboard') }}">Admin Dashboard</a>
        <a href="{{ route('admin.products.index') }}">Products</a>
    @endrole
@endauth
```

### Footer - Conditional Links
```blade
@role('employee')
    <a href="{{ route('admin.products.create') }}">Add New Product</a>
@endrole

@role('customer')
    <a href="{{ route('products.index') }}">Shop All Products</a>
    <a href="{{ route('membership') }}">Membership</a>
@endrole
```

---

## Notes

- The `@role` directive is equivalent to checking `auth()->user()->hasRole('role-name')`
- All directives automatically handle the case where user is not logged in (won't throw errors)
- Routes with `role:customer` middleware will return 403 Forbidden for non-customers
- New users automatically get the `customer` role via `CreateNewUser` action
- The old `role` column still exists but is no longer used
