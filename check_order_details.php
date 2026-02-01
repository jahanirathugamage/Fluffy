<?php
use App\Models\Order;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $orders = Order::all();
    echo "Count: " . $orders->count() . "\n";
    foreach($orders as $o) {
        echo "ID: {$o->id}, Status: '{$o->payment_status}', Amount: {$o->amount}, Created: {$o->created_at}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
