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

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate([]);

        $existingItem = $cart->items()
            ->where('product_id', $this->product->id)
            ->where('specification_id', $this->selectedSpecId)
            ->first();

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
