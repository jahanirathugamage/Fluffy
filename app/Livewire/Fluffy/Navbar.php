<?php

namespace App\Livewire\Fluffy;

use Livewire\Component;

class Navbar extends Component
{
    public bool $mobileOpen = false;
    public bool $cartOpen = false;

    public function openMobile(): void { $this->mobileOpen = true; }
    public function closeMobile(): void { $this->mobileOpen = false; }

    public function openCart(): void { $this->cartOpen = true; }
    public function closeCart(): void { $this->cartOpen = false; }

    public function render()
    {
        return view('livewire.fluffy.navbar');
    }
}
