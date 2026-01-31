<?php
// app\Livewire\Fluffy\Navbar.php
namespace App\Livewire\Fluffy;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public $searchExpanded = false;
    public $searchQuery = '';
    public $cartCount = 0;

    public $hamburgerOpen = false;

    public function mount()
    {
        $this->updateCartCount();
    }

    #[On('cartUpdated')]
    public function updateCartCount()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            $this->cartCount = $cart ? $cart->totalItems() : 0;
        } else {
            $this->cartCount = 0;
        }
    }

    #[On('hamburgerMenuStateChanged')]
    public function syncHamburgerState($isOpen)
    {
        $this->hamburgerOpen = (bool) $isOpen;
    }

    public function toggleSearch()
    {
        $this->searchExpanded = !$this->searchExpanded;

        // Clear search when closing
        if (!$this->searchExpanded) {
            $this->searchQuery = '';
        }
    }

    public function search()
    {
        if (empty($this->searchQuery)) {
            return;
        }

        // Redirect to products page with search query
        return redirect()->route('products.index', ['search' => $this->searchQuery]);
    }

    public function render()
    {
        return view('livewire.fluffy.navbar');
    }
}
