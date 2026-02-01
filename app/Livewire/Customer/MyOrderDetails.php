<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class MyOrderDetails extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        // Strict Access Control: Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $this->order = $order->load(['items.product', 'items.specification']);
    }

    public function confirmOrder()
    {
        // Validation: Can only confirm if shipped and delivery time passed
        if ($this->order->delivery_status === 'shipped' && now()->greaterThanOrEqualTo($this->order->delivery_expected_at)) {
             $this->order->update(['delivery_status' => 'delivered']);
             $this->order->refresh();
             session()->flash('message', 'Thank you! Order marked as delivered.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.customer.my-order-details');
    }
}
