<?php
// app\Livewire\Products\ProductCard.php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
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

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $spec = $this->product->specifications->sortBy('price')->first();

        if (!$spec) {
            return;
        }

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate([]);

        $existingItem = $cart->items()
            ->where('product_id', $this->product->id)
            ->where('specification_id', $spec->id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $this->product->id,
                'specification_id' => $spec->id,
                'quantity' => 1,
            ]);
        }

        $this->dispatch('cartUpdated');
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $existingFav = $user->favorites()
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingFav) {
            $existingFav->delete();
        } else {
            $user->favorites()->create([
                'product_id' => $this->product->id,
                'specification_id' => $this->product->specifications->first()->id ?? null,
            ]);
        }

        // This updates: CartDrawer favorites + ProductCard/ProductDetail hearts (via listener)
        $this->dispatch('favoriteUpdated');
    }

    public function getIsFavoriteProperty()
    {
        if (!Auth::check()) return false;

        return Auth::user()
            ->favorites()
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.products.product-card');
    }
}
