<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class ManageOrders extends Component
{
    public $status = 'all';

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function shipOrder($orderId)
    {
        // 1. Calculate Delivery Date (Tomorrow 8am-8pm)
        $deliveryDate = now()->addDay()->setTime(rand(8, 20), rand(0, 59));

        // 2. Update Order directly (since SP might just handle 'status' column, we need new columns)
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'delivery_status' => 'shipped',
                'delivery_expected_at' => $deliveryDate,
                // Optional: Sync legacy status/payment_status if needed, but we keep payment_status as 'completed'
            ]);
            
            session()->flash('message', 'Order #' . $orderId . ' shipped! ETA: ' . $deliveryDate->format('d M h:i A'));
        }
    }

    #[Layout('layouts.app')] 
    public function render()
    {
        $query = Order::query()->with('user');

        if ($this->status !== 'all') {
            // Filter based on delivery_status now
            $query->where('delivery_status', $this->status);
        }

        $orders = $query->latest()->get();

        return view('livewire.employee.manage-orders', [
            'orders' => $orders
        ]);
    }
}
