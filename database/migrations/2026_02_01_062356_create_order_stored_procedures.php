<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS update_order_status;
            CREATE PROCEDURE update_order_status(IN orderId INT, IN newStatus VARCHAR(255))
            BEGIN
                UPDATE orders 
                SET status = newStatus, updated_at = NOW() 
                WHERE id = orderId;
            END;
        ";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS update_order_status");
    }
};
