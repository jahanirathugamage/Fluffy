<?php
use Illuminate\Support\Facades\DB;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $result = DB::select("SHOW CREATE TABLE products");
    print_r($result);
    
    $result2 = DB::select("SHOW VARIABLES LIKE 'collation%'");
    print_r($result2);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
