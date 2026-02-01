<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add delivery_status column
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_status')->default('processing')->after('amount');
        });

        // 2. Rename status to payment_status.
        // Since it's an enum, we use raw SQL to be safe and also update the definitions if needed,
        // but for now simply renaming the column is the goal.
        // We also want to ensure the default is 'completed' as per user request for "payment_status".
        // Note: The previous values were 'processing', 'shipped' etc.
        // We will assume old 'processing' meant 'paid/completed' in context of payment.

        // Rename column: ALTER TABLE orders CHANGE status payment_status ENUM(...)
        // We redefine the ENUM to be irrelevant to shipping now?
        // Or just keep the column rename. Let's do a raw change to be explicit.

        DB::statement("ALTER TABLE orders CHANGE status payment_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed', 'failed') DEFAULT 'completed'");

        // (Optional) Update existing data: if status was 'processing', set payment_status='completed'
        DB::statement("UPDATE orders SET payment_status = 'completed' WHERE payment_status = 'processing'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert payment_status to status
        DB::statement("ALTER TABLE orders CHANGE payment_status status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed', 'failed') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_status');
        });
    }
};
