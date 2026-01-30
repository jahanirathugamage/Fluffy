<?php

namespace App\Livewire\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

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
        // Livewire automatically re-renders computed properties
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
        return Auth::user()->favorites()->with('product', 'specification')->latest()->take(3)->get();
    }

    public function moveToCart($favoriteId)
    {
        $fav = \App\Models\Favorite::with('product')->find($favoriteId);
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

        $fav->delete();
        $this->dispatch('cartUpdated');
        $this->dispatch('favoriteUpdated'); // In case other components list favorites
    }

    public function render()
    {
        return view('livewire.cart.cart-drawer');
    }
}
