<?php
use Illuminate\Support\Facades\DB;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Recreating stored procedures...\n";
    
    DB::unprepared("DROP PROCEDURE IF EXISTS sp_create_product");
    DB::unprepared("DROP PROCEDURE IF EXISTS sp_update_product");
    DB::unprepared("DROP PROCEDURE IF EXISTS sp_delete_product");
    
    // Explicitly set collation for the session before creating procedures
    DB::statement("SET NAMES utf8mb4 COLLATE utf8mb4_general_ci");
    
    DB::unprepared("
        CREATE PROCEDURE sp_create_product(
            IN p_name VARCHAR(100),
            IN p_category_id INT,
            IN p_animal_id INT,
            IN p_details TEXT,
            IN p_benefits TEXT,
            IN p_nutrition TEXT,
            IN p_image_path VARCHAR(255),
            IN p_spec_name VARCHAR(255),
            IN p_price DECIMAL(10,2),
            IN p_stock INT,
            OUT p_product_id INT,
            OUT p_specification_id INT
        )
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                ROLLBACK;
                RESIGNAL;
            END;
            
            START TRANSACTION;
                INSERT INTO products (name, category_id, animal_id, details, benefits, nutrition, image_path, created_at, updated_at)
                VALUES (p_name, p_category_id, p_animal_id, p_details, p_benefits, p_nutrition, p_image_path, NOW(), NOW());
                
                SET p_product_id = LAST_INSERT_ID();
                
                INSERT INTO specifications (product_id, name, price, stock, created_at, updated_at)
                VALUES (p_product_id, p_spec_name, p_price, p_stock, NOW(), NOW());
                
                SET p_specification_id = LAST_INSERT_ID();
            COMMIT;
        END
    ");
    
    DB::unprepared("
        CREATE PROCEDURE sp_update_product(
            IN p_product_id INT,
            IN p_specification_id INT,
            IN p_name VARCHAR(100),
            IN p_category_id INT,
            IN p_animal_id INT,
            IN p_details TEXT,
            IN p_benefits TEXT,
            IN p_nutrition TEXT,
            IN p_image_path VARCHAR(255),
            IN p_spec_name VARCHAR(255),
            IN p_price DECIMAL(10,2),
            IN p_stock INT
        )
        BEGIN
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                ROLLBACK;
                RESIGNAL;
            END;
            
            START TRANSACTION;
                UPDATE products 
                SET name = p_name,
                    category_id = p_category_id,
                    animal_id = p_animal_id,
                    details = p_details,
                    benefits = p_benefits,
                    nutrition = p_nutrition,
                    image_path = p_image_path,
                    updated_at = NOW()
                WHERE id = p_product_id;
                
                UPDATE specifications
                SET name = p_spec_name,
                    price = p_price,
                    stock = p_stock,
                    updated_at = NOW()
                WHERE id = p_specification_id;
            COMMIT;
        END
    ");
    
    DB::unprepared("
        CREATE PROCEDURE sp_delete_product(
            IN p_specification_id INT,
            OUT p_image_path VARCHAR(255),
            OUT p_animal_name VARCHAR(100)
        )
        BEGIN
            DECLARE v_product_id INT;
            DECLARE v_spec_count INT;
            
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                ROLLBACK;
                RESIGNAL;
            END;
            
            START TRANSACTION;
                -- Get product info before deletion
                SELECT s.product_id, p.image_path, a.name
                INTO v_product_id, p_image_path, p_animal_name
                FROM specifications s
                JOIN products p ON s.product_id = p.id
                JOIN animals a ON p.animal_id = a.id
                WHERE s.id = p_specification_id;
                
                -- Delete specification
                DELETE FROM specifications WHERE id = p_specification_id;
                
                -- Check remaining specifications
                SELECT COUNT(*) INTO v_spec_count
                FROM specifications
                WHERE product_id = v_product_id;
                
                -- Delete product if no specs remain
                IF v_spec_count = 0 THEN
                    DELETE FROM products WHERE id = v_product_id;
                ELSE
                    SET p_image_path = NULL;
                END IF;
            COMMIT;
        END
    ");
    
    echo "Stored procedures recreated successfully.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
