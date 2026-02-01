<?php
// app\Livewire\Employee\Orders.php
namespace App\Livewire\Employee;

use Livewire\Component;

class Orders extends Component
{
    public function render()
    {
        return view('livewire.employee.orders')->layout('layouts.app');
    }
}
