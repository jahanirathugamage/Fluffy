<?php
use Illuminate\Support\Facades\DB;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $config = config('database.connections.mysql');
    echo "Config Collation: " . ($config['collation'] ?? 'NOT SET') . "\n";
    
    $pdo = DB::connection()->getPdo();
    $serverCollation = $pdo->query("SELECT @@collation_connection")->fetchColumn();
    echo "Server Connection Collation: " . $serverCollation . "\n";
    
    $dbCollation = DB::select("SELECT @@collation_database")[0]->{'@@collation_database'};
    echo "Database Collation: " . $dbCollation . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
