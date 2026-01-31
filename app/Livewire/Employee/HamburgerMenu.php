<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HamburgerMenu extends Component
{
    public $isOpen = false;

    protected $listeners = [
        'openHamburgerMenu' => 'open',
        'closeHamburgerMenu' => 'close',
    ];

    public function open()
    {
        $this->isOpen = true;
        // Inform navbar so it can show the "X" icon on mobile
        $this->dispatch('hamburgerMenuStateChanged', isOpen: true);
    }

    public function close()
    {
        $this->isOpen = false;
        // Inform navbar so it can revert back to hamburger icon
        $this->dispatch('hamburgerMenuStateChanged', isOpen: false);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->dispatch('hamburgerMenuStateChanged', isOpen: false);

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.employee.hamburger-menu');
    }
}
