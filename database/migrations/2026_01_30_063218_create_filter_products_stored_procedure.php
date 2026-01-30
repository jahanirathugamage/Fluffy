<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS filter_products');
        
        // Note: This stored procedure is a placeholder for potential future optimization
        // Current implementation uses Eloquent queries directly in the controller
        // Stored procedure commented out due to MariaDB/MySQL JSON handling complexities
        
        /*
        DB::unprepared("
            CREATE PROCEDURE filter_products(
                IN categoryIds VARCHAR(255),
                IN animalIds VARCHAR(255),
                IN inStockOnly BOOLEAN,
                IN minPrice DECIMAL(10,2),
                IN maxPrice DECIMAL(10,2)
            )
            BEGIN
                SELECT DISTINCT p.*, s.price, s.stock
                FROM products p
                INNER JOIN specifications s ON p.id = s.product_id
                WHERE 1=1
                    AND (categoryIds IS NULL OR categoryIds = '' OR FIND_IN_SET(p.category_id, categoryIds) > 0)
                    AND (animalIds IS NULL OR animalIds = '' OR FIND_IN_SET(p.animal_id, animalIds) > 0)
                    AND (NOT inStockOnly OR s.stock > 0)
                    AND (s.price >= minPrice)
                    AND (maxPrice IS NULL OR maxPrice = 0 OR s.price <= maxPrice)
                ORDER BY p.id;
            END
        ");
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS filter_products');
    }
};
