<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Specification;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Animal::where('name', 'Cat')->first();
        $dog = Animal::where('name', 'Dog')->first();
        
        $food = Category::where('name', 'Food')->first();
        
        // Sample Product 1: Whiskas (Cat Food)
        if ($cat && $food) {
            $p1 = Product::create([
                'animal_id' => $cat->id,
                'category_id' => $food->id,
                'name' => 'Whiskas Adult Cat Ocean Fish',
                'details' => 'Complete and balanced nutrition for adult cats.',
                'benefits' => 'Healthy coat and skin.',
                'nutrition' => 'Protein 30%, Fat 10%',
                'image_path' => 'assets/images/cat/whiskas.png', // Ensure this image exists or is placeholder
            ]);
            
            Specification::create([
                'product_id' => $p1->id,
                'name' => '1.2kg',
                'price' => 1650.00,
                'stock' => 50,
            ]);
            
            Specification::create([
                'product_id' => $p1->id,
                'name' => '450g',
                'price' => 750.00,
                'stock' => 100,
            ]);
        }
        
        // Sample Product 2: Woofbix (Dog Food)
        if ($dog && $food) {
            $p2 = Product::create([
                'animal_id' => $dog->id,
                'category_id' => $food->id,
                'name' => 'Woofbix Dog Biscuits (Chicken)',
                'details' => 'Tasty chicken flavored biscuits.',
                'benefits' => 'Great for training.',
                'nutrition' => 'Protein 15%, Fiber 5%',
                'image_path' => 'assets/images/dog/woofbix.png', 
            ]);
            
            Specification::create([
                'product_id' => $p2->id,
                'name' => '200g',
                'price' => 580.00,
                'stock' => 20,
            ]);
        }
    }
}
