<?php
use App\Models\Order;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Total Orders: " . Order::count() . "\n";
    $statuses = Order::select('payment_status')->distinct()->pluck('payment_status');
    echo "Payment Statuses found: " . json_encode($statuses) . "\n";
    
    $paidOrders = Order::where('payment_status', 'paid')->count();
    echo "Paid Orders Count (exact 'paid'): " . $paidOrders . "\n";
    
    $paidOrdersLower = Order::whereRaw('LOWER(payment_status) = ?', ['paid'])->count();
    echo "Paid Orders Count (case-insensitive 'paid'): " . $paidOrdersLower . "\n";

    $totalAmount = Order::sum('amount');
    echo "Total Amount (all orders): " . $totalAmount . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
