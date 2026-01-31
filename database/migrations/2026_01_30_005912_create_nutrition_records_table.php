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
        Schema::create('nutrition_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->date('meal_date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->string('food_name');
            $table->decimal('portion_size', 8, 2); // in grams

            // Nutritional values
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable(); // in grams
            $table->decimal('carbohydrates', 8, 2)->nullable(); // in grams
            $table->decimal('fat', 8, 2)->nullable(); // in grams
            $table->decimal('fiber', 8, 2)->nullable(); // in grams

            // Micronutrients
            $table->decimal('calcium', 8, 2)->nullable(); // in mg
            $table->decimal('iron', 8, 2)->nullable(); // in mg
            $table->decimal('zinc', 8, 2)->nullable(); // in mg
            $table->decimal('vitamin_a', 8, 2)->nullable(); // in mcg
            $table->decimal('vitamin_c', 8, 2)->nullable(); // in mg
            $table->decimal('vitamin_d', 8, 2)->nullable(); // in mcg

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_records');
    }
};
