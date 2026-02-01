<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ManageOrderDetails extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load(['items.product', 'items.specification', 'user']);
    }

    public function shipOrder()
    {
        $deliveryDate = now()->addDay()->setTime(rand(8, 20), rand(0, 59));
        
        $this->order->update([
            'delivery_status' => 'shipped',
            'delivery_expected_at' => $deliveryDate,
        ]);
        
        $this->order->refresh();
        session()->flash('message', 'Order shipped! ETA: ' . $deliveryDate->format('d M h:i A'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.employee.manage-order-details');
    }
}
