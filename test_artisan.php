<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "Step 1: Start\n";
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;

echo "Step 2: Require autoload\n";
require __DIR__.'/vendor/autoload.php';

echo "Step 3: Require bootstrap\n";
$app = require_once __DIR__.'/bootstrap/app.php';
echo "Step 4: Bootstrap loaded. Type: " . gettype($app) . "\n";

echo "Step 5: Instantiate ArgvInput\n";
$input = new ArgvInput;
echo "Step 6: Handle Command\n";
$status = $app->handleCommand($input);
echo "Step 7: Check status\n";

exit($status);
