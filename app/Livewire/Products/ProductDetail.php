<?php
// app\Livewire\Products\ProductDetail.php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;
    public $selectedSpecId;
    public $quantity = 1;

    public $openSection = 'details';

    public function mount(Product $product)
    {
        // If employee, redirect to dashboard
        if (auth()->check() && (auth()->user()->hasRole('employee') || auth()->user()->can('view-dashboard'))) {
            return redirect()->route('employee.manage-products');
        }

        $this->product = $product->load('specifications', 'category');

        $firstSpec = $this->product->specifications->sortBy('price')->first();
        $this->selectedSpecId = $firstSpec ? $firstSpec->id : null;
    }

    /**
     * When favorites change anywhere (e.g., removed from CartDrawer),
     * re-render so the heart updates in real time.
     */
    #[On('favoriteUpdated')]
    public function refreshFavoriteState()
    {
        // No-op: Livewire will re-render the component.
    }

    public function toggleSection($section)
    {
        $this->openSection = ($this->openSection === $section) ? null : $section;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$this->selectedSpecId) {
            return;
        }

        $spec = $this->getCurrentSpecProperty();

        // Stock Check 1: Is it completely OOS?
        if (!$spec || $spec->stock <= 0) {
            $this->addError('quantity', 'This item is out of stock.');
            return;
        }

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate([]);

        $existingItem = $cart->items()
            ->where('product_id', $this->product->id)
            ->where('specification_id', $this->selectedSpecId)
            ->first();

        $currentCartQty = $existingItem ? $existingItem->quantity : 0;
        $totalRequested = $currentCartQty + $this->quantity;

        // Stock Check 2: Does total exceed stock?
        if ($totalRequested > $spec->stock) {
            $remaining = max(0, $spec->stock - $currentCartQty);
            $this->addError('quantity', "Only {$remaining} more item(s) can be added (Stock: {$spec->stock}).");
            return;
        }

        if ($existingItem) {
            $existingItem->increment('quantity', $this->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $this->product->id,
                'specification_id' => $this->selectedSpecId,
                'quantity' => $this->quantity,
            ]);
        }

        $this->dispatch('cartUpdated');
        // Optional: Dispatch success message or similar
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $fav = $user->favorites()
            ->where('product_id', $this->product->id)
            ->first();

        if ($fav) {
            $fav->delete();
        } else {
            $user->favorites()->create([
                'product_id' => $this->product->id,
                'specification_id' => $this->selectedSpecId,
            ]);
        }

        // Updates CartDrawer favorites + ProductCard/ProductDetail hearts
        $this->dispatch('favoriteUpdated');
    }

    public function getCurrentSpecProperty()
    {
        return $this->product->specifications->find($this->selectedSpecId);
    }

    public function getIsFavoriteProperty()
    {
        if (!Auth::check()) return false;

        return Auth::user()
            ->favorites()
            ->where('product_id', $this->product->id)
            ->exists();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.products.product-detail');
    }
}
