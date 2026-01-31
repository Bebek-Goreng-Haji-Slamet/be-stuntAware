<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'meal_date',
        'meal_type',
        'food_name',
        'portion_size',
        'calories',
        'protein',
        'carbohydrates',
        'fat',
        'fiber',
        'calcium',
        'iron',
        'zinc',
        'vitamin_a',
        'vitamin_c',
        'vitamin_d',
        'notes',
    ];

    protected $casts = [
        'meal_date' => 'date',
        'portion_size' => 'decimal:2',
        'calories' => 'decimal:2',
        'protein' => 'decimal:2',
        'carbohydrates' => 'decimal:2',
        'fat' => 'decimal:2',
        'fiber' => 'decimal:2',
        'calcium' => 'decimal:2',
        'iron' => 'decimal:2',
        'zinc' => 'decimal:2',
        'vitamin_a' => 'decimal:2',
        'vitamin_c' => 'decimal:2',
        'vitamin_d' => 'decimal:2',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
