<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Cat', 'Dog', 'Hamster', 'Rabbit'] as $name) {
            Animal::firstOrCreate(['name' => $name]);
        }
    }
}
