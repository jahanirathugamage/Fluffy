<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->handle(
        $input = new Symfony\Component\Console\Input\ArgvInput,
        new Symfony\Component\Console\Output\ConsoleOutput
    );
    exit($status);
} catch (Throwable $e) {
    echo "EXCEPTION CAUGHT:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack Trace:\n";
    echo $e->getTraceAsString();
}
