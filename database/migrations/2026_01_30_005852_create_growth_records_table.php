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
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->date('measurement_date');
            $table->integer('age_in_months');
            $table->decimal('weight', 5, 2); // in kg
            $table->decimal('height', 5, 2); // in cm
            $table->decimal('head_circumference', 5, 2)->nullable(); // in cm

            // Z-scores
            $table->decimal('weight_for_age_zscore', 5, 2)->nullable();
            $table->decimal('height_for_age_zscore', 5, 2)->nullable();
            $table->decimal('weight_for_height_zscore', 5, 2)->nullable();

            // Status assessment
            $table->enum('stunting_status', ['normal', 'at_risk', 'stunted', 'severely_stunted'])->nullable();
            $table->enum('wasting_status', ['normal', 'wasted', 'severely_wasted'])->nullable();
            $table->enum('underweight_status', ['normal', 'underweight', 'severely_underweight'])->nullable();

            $table->text('ai_analysis')->nullable();
            $table->text('recommendations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_records');
    }
};
