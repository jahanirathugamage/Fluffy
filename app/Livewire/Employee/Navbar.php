<?php
// app\Livewire\Employee\Navbar.php
namespace App\Livewire\Employee;

use Livewire\Component;

class Navbar extends Component
{
    public $showSidebar = false;

    public function toggleSidebar()
    {
        $this->dispatch('openHamburgerMenu');
    }

    public function closeSidebar()
    {
        $this->dispatch('closeHamburgerMenu');
    }

    public function render()
    {
        return view('livewire.employee.navbar');
    }
}
