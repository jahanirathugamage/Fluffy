<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    foreach (['Accessories', 'Food', 'Grooming', 'Sustainable', 'Toys'] as $name) {
        Category::firstOrCreate(['name' => $name]);
    }
}
}
