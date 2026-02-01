<?php
use App\Models\Order;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $validStatuses = ['paid', 'completed', 'processing'];
    
    // 1. Total Sales
    $totalSales = Order::whereIn('payment_status', $validStatuses)->sum('amount');
    $formattedSales = number_format($totalSales / 100, 2);
    echo "Total Sales: $formattedSales ($totalSales)\n";
    
    // 2. Total Orders
    $totalOrders = Order::count();
    echo "Total Orders: $totalOrders\n";
    
    // 3. Avg Order Value
    $avg = $totalOrders > 0 ? number_format(($totalSales / 100) / $totalOrders, 2) : '0.00';
    echo "Avg Order Value: $avg\n";
    
    // 4. Chart Data
    $salesData = Order::selectRaw('DATE(created_at) as date, SUM(amount) as total')
        ->whereIn('payment_status', $validStatuses)
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
    echo "Chart Data Points: " . $salesData->count() . "\n";
    foreach($salesData as $d) {
        echo "Date: {$d->date}, Total: {$d->total}\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
