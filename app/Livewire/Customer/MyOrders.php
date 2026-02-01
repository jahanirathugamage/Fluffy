<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class MyOrders extends Component
{
    public $status = 'all';

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function confirmOrder($orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        
        // Validation: Can only confirm if shipped and delivery time passed
        if ($order->delivery_status === 'shipped' && now()->greaterThanOrEqualTo($order->delivery_expected_at)) {
             $order->update(['delivery_status' => 'delivered']);
             session()->flash('message', 'Order marked as delivered!');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Order::where('user_id', Auth::id())->with('items.product');

        if ($this->status !== 'all') {
            $query->where('delivery_status', $this->status);
        }

        $orders = $query->latest()->get();

        return view('livewire.customer.my-orders', [
            'orders' => $orders
        ]);
    }
}
