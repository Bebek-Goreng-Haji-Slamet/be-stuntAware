<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'measurement_date',
        'age_in_months',
        'weight',
        'height',
        'head_circumference',
        'weight_for_age_zscore',
        'height_for_age_zscore',
        'weight_for_height_zscore',
        'stunting_status',
        'wasting_status',
        'underweight_status',
        'ai_analysis',
        'recommendations',
    ];

    protected $casts = [
        'measurement_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'weight_for_age_zscore' => 'decimal:2',
        'height_for_age_zscore' => 'decimal:2',
        'weight_for_height_zscore' => 'decimal:2',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function isStunted(): bool
    {
        return in_array($this->stunting_status, ['stunted', 'severely_stunted']);
    }

    public function needsIntervention(): bool
    {
        return in_array($this->stunting_status, ['at_risk', 'stunted', 'severely_stunted']) ||
            in_array($this->wasting_status, ['wasted', 'severely_wasted']) ||
            in_array($this->underweight_status, ['underweight', 'severely_underweight']);
    }
}
