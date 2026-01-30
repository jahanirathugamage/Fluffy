<?php

namespace App\Livewire\Favorites;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\CartItem;

class Index extends Component
{
    public function remove($favoriteId)
    {
        $fav = Favorite::find($favoriteId);
        if ($fav && $fav->user_id === Auth::id()) {
            $fav->delete();
        }
    }

    public function moveToCart($favoriteId)
    {
        $fav = Favorite::with('product')->find($favoriteId);
        if (!$fav || $fav->user_id !== Auth::id()) return;

        $cart = Auth::user()->cart()->firstOrCreate([]);
        
        // Use fav spec or defaut
        // Fav table has specification_id nullable.
        // If null, find first spec.
        $specId = $fav->specification_id 
            ?? $fav->product->specifications->sortBy('price')->first()->id 
            ?? null;

        if (!$specId) {
             // Product has no specs? Should not happen.
             return;
        }

        // Add to cart
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

        // Remove from favorites
        $fav->delete();

        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        $favorites = Auth::check() 
            ? Auth::user()->favorites()->with(['product', 'specification'])->latest()->get() 
            : collect();

        return view('livewire.favorites.index', [
            'favorites' => $favorites
        ])->layout('layouts.app');
    }
}
