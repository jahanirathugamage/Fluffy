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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('animal_id')->constrained('animals')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();

            $table->string('name', 100);
            $table->text('details');
            $table->text('benefits');
            $table->text('nutrition');
            $table->string('image_path', 255);

            $table->timestamps();

            // Indexes for filtering/search
            $table->index('name');
            $table->index('animal_id');
            $table->index('category_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
