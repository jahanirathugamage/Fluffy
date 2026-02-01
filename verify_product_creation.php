<?php
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Animal;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting product verification...\n";
    
    // Get valid foreign keys
    $category = Category::first();
    $animal = Animal::first();
    
    if (!$category || !$animal) {
        die("Error: Need at least one category and animal in database.\n");
    }
    
    DB::beginTransaction();
    echo "Transaction started.\n";
    
    // Test data
    $name = 'Test Product ' . time();
    $details = 'Test Details';
    $benefits = 'Test Benefits';
    $nutrition = 'Test Nutrition';
    $imagePath = 'assets/images/test.jpg';
    $specName = 'Test Spec';
    $price = 100.00;
    $stock = 10;
    
    echo "Calling stored procedure...\n";
    DB::statement('CALL sp_create_product(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_product_id, @p_specification_id)', [
        $name,
        $category->id,
        $animal->id,
        $details,
        $benefits,
        $nutrition,
        $imagePath,
        $specName,
        $price,
        $stock
    ]);
    
    $result = DB::select('SELECT @p_product_id as product_id, @p_specification_id as spec_id');
    $productId = $result[0]->product_id;
    $specId = $result[0]->spec_id;
    
    echo "Stored procedure executed. Product ID: $productId, Spec ID: $specId\n";
    
    if ($productId && $specId) {
        echo "SUCCESS: Product and specification created via stored procedure.\n";
    } else {
        echo "FAILURE: Stored procedure did not return IDs.\n";
    }
    
    DB::rollBack();
    echo "Transaction rolled back (cleanup).\n";
    
} catch (\Exception $e) {
    file_put_contents('verification_error.log', $e->getMessage() . "\n" . $e->getTraceAsString());
    echo "EXCEPTION: See verification_error.log for details.\n";
}
