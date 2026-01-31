<?php
// app\Livewire\Employee\Navbar.php
namespace App\Livewire\Employee;

use Livewire\Component;

class Navbar extends Component
{
    public $showSidebar = false;

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
    }

    public function closeSidebar()
    {
        $this->showSidebar = false;
    }

    public function render()
    {
        return view('livewire.employee.navbar');
    }
}
