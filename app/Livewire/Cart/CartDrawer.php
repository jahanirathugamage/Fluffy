<?php
// app\Livewire\Cart\CartDrawer.php

namespace App\Livewire\Cart;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public $isOpen = false;

    #[On('openCart')]
    public function openCart()
    {
        $this->isOpen = true;
    }

    #[On('closeCart')]
    public function closeCart()
    {
        $this->isOpen = false;
    }

    #[On('cartUpdated')]
    public function refreshCart()
    {
        // no-op: computed props re-render
    }

    #[On('favoriteUpdated')]
    public function refreshFavorites()
    {
        // no-op: computed props re-render
    }

    public function increment($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->cart->user_id === Auth::id()) {
            $item->increment('quantity');
            $this->dispatch('cartUpdated');
        }
    }

    public function decrement($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->cart->user_id === Auth::id()) {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
            } else {
                $item->delete();
            }
            $this->dispatch('cartUpdated');
        }
    }

    public function remove($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->cart->user_id === Auth::id()) {
            $item->delete();
            $this->dispatch('cartUpdated');
        }
    }

    public function getCartItemsProperty()
    {
        if (!Auth::check()) return collect();

        $cart = Auth::user()->cart;
        return $cart ? $cart->items()->with('product', 'specification')->get() : collect();
    }

    public function getTotalProperty()
    {
        if (!Auth::check()) return 0;

        $cart = Auth::user()->cart;
        return $cart ? $cart->totalPrice() : 0;
    }

    public function getCartCountProperty()
    {
        if (!Auth::check()) return 0;

        $cart = Auth::user()->cart;
        return $cart ? $cart->totalItems() : 0;
    }

    public function getFavoritesProperty()
    {
        if (!Auth::check()) return collect();

        return Auth::user()
            ->favorites()
            ->with('product', 'specification')
            ->latest()
            ->take(3)
            ->get();
    }

    /**
     * Logic 2: Move favorite into cart but keep it in favorites.
     */
    public function moveToCart($favoriteId)
    {
        $fav = \App\Models\Favorite::with('product.specifications')->find($favoriteId);
        if (!$fav || $fav->user_id !== Auth::id()) return;

        $cart = Auth::user()->cart()->firstOrCreate([]);

        $specId = $fav->specification_id
            ?? $fav->product->specifications->sortBy('price')->first()->id
            ?? null;

        if (!$specId) return;

        $existing = $cart->items()
            ->where('product_id', $fav->product_id)
            ->where('specification_id', $specId)
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $fav->product_id,
                'specification_id' => $specId,
                'quantity' => 1
            ]);
        }

        // DO NOT delete favorite anymore
        $this->dispatch('cartUpdated');
    }

    /**
     * Logic 1: Removing a favorite should update hearts in real time.
     */
    public function removeFavorite($favoriteId)
    {
        $fav = \App\Models\Favorite::find($favoriteId);
        if (!$fav || $fav->user_id !== Auth::id()) return;

        $fav->delete();

        // Hearts update in ProductCard + ProductDetail via #[On('favoriteUpdated')]
        $this->dispatch('favoriteUpdated');

        // If your cart badge / drawer depends on this, keep it:
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart.cart-drawer');
    }
}
