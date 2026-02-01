<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{

    public $totalSales;
    public $totalOrders;
    public $formattedSales;
    
    public function render()
    {
        // 1. Total Sales (paid, completed, processing)
        $validStatuses = ['paid', 'completed', 'processing'];
        $this->totalSales = Order::whereIn('payment_status', $validStatuses)->sum('amount');
        $this->formattedSales = number_format($this->totalSales / 100, 2);
        
        // 2. Total Orders
        $this->totalOrders = Order::count();
        
        // 3. Sales Chart Data (Last 7 Days)
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereIn('payment_status', $validStatuses)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $salesChartLabels = [];
        $salesChartValues = [];
        
        // Fill in missing days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $salesChartLabels[] = now()->subDays($i)->format('M d'); // e.g., Oct 25
            $found = $salesData->firstWhere('date', $date);
            $salesChartValues[] = $found ? $found->total / 100 : 0;
        }

        // 4. Order Status Distribution
        $statusData = Order::selectRaw('delivery_status, COUNT(*) as count')
            ->groupBy('delivery_status')
            ->get();
            
        $statusLabels = $statusData->pluck('delivery_status')->map(fn($s) => ucfirst($s))->toArray();
        $statusValues = $statusData->pluck('count')->toArray();

        // 5. Top Products
        $topProducts = \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
            
        return view('livewire.employee.dashboard', [
            'salesChartLabels' => $salesChartLabels,
            'salesChartValues' => $salesChartValues,
            'statusLabels' => $statusLabels,
            'statusValues' => $statusValues,
            'topProducts' => $topProducts
        ])->layout('layouts.app');
    }
}
