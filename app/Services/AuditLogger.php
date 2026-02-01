<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public static function log(array $data): void
    {
        Log::channel('stderr')->info('Audit Log:', $data);
    }
}
