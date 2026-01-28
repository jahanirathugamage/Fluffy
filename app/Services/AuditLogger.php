<?php

namespace App\Services;

use MongoDB\Client;

class AuditLogger
{
    public static function log(array $data): void
    {
        $client = new Client("mongodb://127.0.0.1:27017");
        $collection = $client->fluffy_logs->audit_logs;

        $collection->insertOne([
            ...$data,
            'created_at' => now()
        ]);
    }
}
