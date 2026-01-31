<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth_date',
        'birth_weight',
        'birth_height',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_weight' => 'decimal:2',
        'birth_height' => 'decimal:2',
    ];

    protected $appends = [
        'age_in_months',
        'age_in_years',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function growthRecords(): HasMany
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function nutritionRecords(): HasMany
    {
        return $this->hasMany(NutritionRecord::class);
    }

    public function getAgeInMonthsAttribute(): int
    {
        return $this->birth_date->diffInMonths(now());
    }

    public function getAgeInYearsAttribute(): int
    {
        return $this->birth_date->diffInYears(now());
    }

    public function getLatestGrowthRecord()
    {
        return $this->growthRecords()->latest('measurement_date')->first();
    }
}
