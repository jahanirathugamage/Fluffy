<?php

use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "ID | Name | Email | Roles | Permissions via Roles\n";
echo "---|---|---|---|---\n";

foreach (User::all() as $user) {
    $roles = $user->getRoleNames()->implode(', ');
    $permissions = $user->getPermissionsViaRoles()->pluck('name')->implode(', ');
    
    echo "{$user->id} | {$user->name} | {$user->email} | [{$roles}] | [{$permissions}]\n";
}
