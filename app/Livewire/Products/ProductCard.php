<?php

namespace App\Livewire\Products;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Default to the first specification (usually default price/size)
        // In a real app with strict variants, we might redirect to detail.
        // Legacy app added "data-spec", likely the first one found.
        $spec = $this->product->specifications->sortBy('price')->first();

        if (!$spec) {
            // Should not happen if data is seeded correctly
            return; 
        }

        $user = Auth::user();
        $cart = $user->cart()->firstOrCreate([]);

        // specific cart logic - find if item exists
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

        $this->dispatch('cartUpdated'); // To update CartDrawer count/content
        
        // Optional: Toast notification
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        // Check if favorite exists for this product (any spec or general)
        // Legacy favs were specific, but UI heart is usually per product on grid.
        // We'll toggle "any favorite for this product".
       
        // We'll use the first spec for consistency or just product_id if spec is nullable.
        // DB Schema has nullable spec for favorites.
        // Let's toggle a "general" favorite for the product if spec is not relevant on grid.
        
        // Correction: Schema said spec_id is nullable.
        // Let's try to find an existing fav for this product.
        $existingFav = $user->favorites()->where('product_id', $this->product->id)->first();

        if ($existingFav) {
            $existingFav->delete();
        } else {
            // Add favorite (general)
            $user->favorites()->create([
                'product_id' => $this->product->id,
                'specification_id' => $this->product->specifications->first()->id ?? null,
            ]);
        }

        $this->dispatch('favoriteUpdated');
    }

    public function getIsFavoriteProperty()
    {
         if (!Auth::check()) return false;
         return Auth::user()->favorites()->where('product_id', $this->product->id)->exists();
    }

    public function render()
    {
        return view('livewire.products.product-card');
    }
}
