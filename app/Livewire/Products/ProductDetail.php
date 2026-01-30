<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class ProductDetail extends Component
{
    public Product $product;
    public $selectedSpecId;
    public $quantity = 1;
    
    // UI State for Accordion
    public $openSection = 'details'; // 'details', 'benefits', 'nutrition', or null

    public function mount(Product $product)
    {
        $this->product = $product->load('specifications', 'category');
        
        // Default to first spec sorted by price
        $firstSpec = $this->product->specifications->sortBy('price')->first();
        $this->selectedSpecId = $firstSpec ? $firstSpec->id : null;
    }

    public function updatedSelectedSpecId()
    {
        // Reset quantity or validation if needed
    }

    public function toggleSection($section)
    {
        if ($this->openSection === $section) {
            $this->openSection = null;
        } else {
            $this->openSection = $section;
        }
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
            // Should add error message to UI
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
        // Optional: notification
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        // Since we are on detail page, and user picked a spec...
        // But favorites are usually just product-level in this simplified migration unless user wants spec-specific favorites list.
        // Legacy code: stored spec name.
        // Let's store unique per product (toggle).
        
        $fav = $user->favorites()->where('product_id', $this->product->id)->first();

        if ($fav) {
            $fav->delete();
        } else {
            $user->favorites()->create([
                'product_id' => $this->product->id,
                'specification_id' => $this->selectedSpecId, // Store specific spec? Why not.
            ]);
        }

        $this->dispatch('favoriteUpdated');
    }

    public function getCurrentSpecProperty()
    {
        return $this->product->specifications->find($this->selectedSpecId);
    }

    public function getIsFavoriteProperty()
    {
        if (!Auth::check()) return false;
        return Auth::user()->favorites()->where('product_id', $this->product->id)->exists();
    }

    #[Layout('layouts.app')] 
    public function render()
    {
        return view('livewire.products.product-detail');
    }
}
