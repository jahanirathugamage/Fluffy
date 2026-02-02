<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "Loading autoloader...\n";
require __DIR__.'/vendor/autoload.php';

echo "Loading bootstrap...\n";
try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "Bootstrap loaded type: " . gettype($app) . "\n";
    
    echo "Making Kernel...\n";
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    echo "Kernel made.\n";
    
    echo "Bootstrapping...\n";
    $kernel->bootstrap();
    echo "Bootstrapped successfully.\n";
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    // echo $e->getTraceAsString();
}
