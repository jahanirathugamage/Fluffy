<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this line for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('delivery_expected_at')->nullable()->after('amount');
            // We need to modify status to include new values.
            // Since enum modification is tricky in standard schema builders across drivers,
            // and often requires raw SQL or specific package support, we'll try a raw modification for MySQL.
            // If strictly using Blueprint, we'd drop and re-add.
            // But let's assume we can just change the column definition or add a new one.
            // Actually, best practice to avoid data loss if we had data is raw sql.
            // For this task, assuming fresh-ish database or just extending:
        });

        // Changing ENUM in mysql requires RAW statement usually or doctrine/dbal
        // We will just perform a raw statement to ensure it works cleanly for this specific requirement.
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed', 'failed') DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
             $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('email')->nullable(); // Re-add email
            $table->dropColumn('delivery_expected_at');
        });
        // Revert status enum logic is complex, generally ignored in down for simple enum expansions unless strict.
    }
};
