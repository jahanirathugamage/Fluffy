<?php

use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'jahanigaveshii@gmail.com';
$user = User::where('email', $email)->first();

if ($user) {
    if (!$user->hasRole('employee')) {
        $user->assignRole('employee');
        echo "✅ Assigned 'employee' role to {$user->name} ({$user->email}).\n";
    } else {
        echo "ℹ️ User {$user->name} ({$user->email}) already has the 'employee' role.\n";
    }
    
    // Check permissions
    echo "Current Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', ') . "\n";
} else {
    echo "❌ User with email {$email} not found.\n";
}
