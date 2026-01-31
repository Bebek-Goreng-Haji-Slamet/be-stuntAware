<?php

namespace App\Services;

use App\Models\Child;
use Carbon\Carbon;

class NutritionCalculatorService
{
    /**
     * Calculate daily nutritional needs based on age
     * Based on Indonesian RDA (Angka Kecukupan Gizi)
     */
    public function getDailyNutritionalNeeds(int $ageInMonths): array
    {
        // Age ranges in months: 0-5, 6-11, 12-23, 24-59
        if ($ageInMonths < 6) {
            return [
                'calories' => 550,
                'protein' => 9, // grams
                'fat' => 31, // grams
                'carbohydrates' => 58, // grams
                'calcium' => 200, // mg
                'iron' => 0.3, // mg (from breast milk)
                'zinc' => 3, // mg
                'vitamin_a' => 375, // mcg
                'vitamin_c' => 40, // mg
                'vitamin_d' => 5, // mcg
            ];
        } elseif ($ageInMonths < 12) {
            return [
                'calories' => 725,
                'protein' => 11,
                'fat' => 36,
                'carbohydrates' => 82,
                'calcium' => 250,
                'iron' => 11,
                'zinc' => 3,
                'vitamin_a' => 400,
                'vitamin_c' => 50,
                'vitamin_d' => 5,
            ];
        } elseif ($ageInMonths < 24) {
            return [
                'calories' => 1125,
                'protein' => 13,
                'fat' => 44,
                'carbohydrates' => 155,
                'calcium' => 650,
                'iron' => 7,
                'zinc' => 3,
                'vitamin_a' => 400,
                'vitamin_c' => 40,
                'vitamin_d' => 15,
            ];
        } else {
            return [
                'calories' => 1350,
                'protein' => 20,
                'fat' => 50,
                'carbohydrates' => 180,
                'calcium' => 650,
                'iron' => 8,
                'zinc' => 4,
                'vitamin_a' => 450,
                'vitamin_c' => 45,
                'vitamin_d' => 15,
            ];
        }
    }

    /**
     * Calculate nutrition from food database
     * This uses a simplified food database
     */
    public function calculateNutritionFromFood(string $foodName, float $portionGrams): array
    {
        $foodDatabase = $this->getFoodDatabase();

        $foodKey = strtolower(trim($foodName));

        if (!isset($foodDatabase[$foodKey])) {
            // Return default if food not found
            return [
                'calories' => 0,
                'protein' => 0,
                'carbohydrates' => 0,
                'fat' => 0,
                'fiber' => 0,
                'calcium' => 0,
                'iron' => 0,
                'zinc' => 0,
                'vitamin_a' => 0,
                'vitamin_c' => 0,
                'vitamin_d' => 0,
            ];
        }

        $foodData = $foodDatabase[$foodKey];
        $factor = $portionGrams / 100; // Database values are per 100g

        return [
            'calories' => round($foodData['calories'] * $factor, 2),
            'protein' => round($foodData['protein'] * $factor, 2),
            'carbohydrates' => round($foodData['carbohydrates'] * $factor, 2),
            'fat' => round($foodData['fat'] * $factor, 2),
            'fiber' => round($foodData['fiber'] * $factor, 2),
            'calcium' => round($foodData['calcium'] * $factor, 2),
            'iron' => round($foodData['iron'] * $factor, 2),
            'zinc' => round($foodData['zinc'] * $factor, 2),
            'vitamin_a' => round($foodData['vitamin_a'] * $factor, 2),
            'vitamin_c' => round($foodData['vitamin_c'] * $factor, 2),
            'vitamin_d' => round($foodData['vitamin_d'] * $factor, 2),
        ];
    }

    /**
     * Get daily nutrition summary for a child
     */
    public function getDailySummary(Child $child, Carbon $date): array
    {
        $nutritionRecords = $child->nutritionRecords()
            ->whereDate('meal_date', $date)
            ->get();

        $total = [
            'calories' => 0,
            'protein' => 0,
            'carbohydrates' => 0,
            'fat' => 0,
            'fiber' => 0,
            'calcium' => 0,
            'iron' => 0,
            'zinc' => 0,
            'vitamin_a' => 0,
            'vitamin_c' => 0,
            'vitamin_d' => 0,
        ];

        foreach ($nutritionRecords as $record) {
            $total['calories'] += $record->calories ?? 0;
            $total['protein'] += $record->protein ?? 0;
            $total['carbohydrates'] += $record->carbohydrates ?? 0;
            $total['fat'] += $record->fat ?? 0;
            $total['fiber'] += $record->fiber ?? 0;
            $total['calcium'] += $record->calcium ?? 0;
            $total['iron'] += $record->iron ?? 0;
            $total['zinc'] += $record->zinc ?? 0;
            $total['vitamin_a'] += $record->vitamin_a ?? 0;
            $total['vitamin_c'] += $record->vitamin_c ?? 0;
            $total['vitamin_d'] += $record->vitamin_d ?? 0;
        }

        $needs = $this->getDailyNutritionalNeeds($child->age_in_months);

        $percentage = [];
        foreach ($needs as $nutrient => $need) {
            $percentage[$nutrient] = $need > 0 ? round(($total[$nutrient] / $need) * 100, 1) : 0;
        }

        return [
            'date' => $date->format('Y-m-d'),
            'total_intake' => $total,
            'daily_needs' => $needs,
            'percentage_met' => $percentage,
            'meal_count' => $nutritionRecords->count(),
            'assessment' => $this->assessNutritionalAdequacy($percentage),
        ];
    }

    /**
     * Assess nutritional adequacy
     */
    private function assessNutritionalAdequacy(array $percentages): array
    {
        $critical = [];
        $warnings = [];
        $good = [];

        foreach ($percentages as $nutrient => $percentage) {
            if ($percentage < 50) {
                $critical[] = $nutrient;
            } elseif ($percentage < 80) {
                $warnings[] = $nutrient;
            } else {
                $good[] = $nutrient;
            }
        }

        $message = "";
        if (!empty($critical)) {
            $message = "⚠ PERHATIAN: Asupan sangat kurang untuk: " . implode(', ', $critical);
        } elseif (!empty($warnings)) {
            $message = "⚠ Asupan perlu ditingkatkan untuk: " . implode(', ', $warnings);
        } else {
            $message = "✓ Asupan gizi hari ini sudah baik!";
        }

        return [
            'status' => empty($critical) ? (empty($warnings) ? 'good' : 'needs_improvement') : 'critical',
            'message' => $message,
            'critical_nutrients' => $critical,
            'warning_nutrients' => $warnings,
        ];
    }

    /**
     * Get food recommendations based on deficiencies
     */
    public function getFoodRecommendations(array $deficientNutrients): array
    {
        $recommendations = [
            'protein' => ['Telur', 'Ayam', 'Ikan', 'Tempe', 'Tahu', 'Daging sapi'],
            'calcium' => ['Susu', 'Keju', 'Yogurt', 'Ikan teri', 'Bayam'],
            'iron' => ['Daging merah', 'Hati ayam', 'Bayam', 'Kacang merah'],
            'zinc' => ['Daging sapi', 'Ayam', 'Kacang-kacangan', 'Biji-bijian'],
            'vitamin_a' => ['Wortel', 'Ubi jalar', 'Bayam', 'Labu', 'Hati ayam'],
            'vitamin_c' => ['Jeruk', 'Pepaya', 'Jambu biji', 'Tomat', 'Brokoli'],
            'vitamin_d' => ['Ikan salmon', 'Telur', 'Susu fortifikasi', 'Sinar matahari pagi'],
        ];

        $result = [];
        foreach ($deficientNutrients as $nutrient) {
            if (isset($recommendations[$nutrient])) {
                $result[$nutrient] = $recommendations[$nutrient];
            }
        }

        return $result;
    }

    /**
     * Simplified Indonesian food database (per 100g)
     */
    private function getFoodDatabase(): array
    {
        return [
            // Protein sources
            'telur' => [
                'calories' => 155,
                'protein' => 13,
                'carbohydrates' => 1.1,
                'fat' => 11,
                'fiber' => 0,
                'calcium' => 50,
                'iron' => 1.2,
                'zinc' => 1.1,
                'vitamin_a' => 160,
                'vitamin_c' => 0,
                'vitamin_d' => 2,
            ],
            'ayam' => [
                'calories' => 239,
                'protein' => 27,
                'carbohydrates' => 0,
                'fat' => 14,
                'fiber' => 0,
                'calcium' => 11,
                'iron' => 0.9,
                'zinc' => 1.3,
                'vitamin_a' => 16,
                'vitamin_c' => 0,
                'vitamin_d' => 0.1,
            ],
            'ikan' => [
                'calories' => 206,
                'protein' => 22,
                'carbohydrates' => 0,
                'fat' => 12,
                'fiber' => 0,
                'calcium' => 12,
                'iron' => 0.4,
                'zinc' => 0.6,
                'vitamin_a' => 40,
                'vitamin_c' => 0,
                'vitamin_d' => 10,
            ],
            'tempe' => [
                'calories' => 193,
                'protein' => 19,
                'carbohydrates' => 9,
                'fat' => 11,
                'fiber' => 1.4,
                'calcium' => 155,
                'iron' => 2.7,
                'zinc' => 1.1,
                'vitamin_a' => 0,
                'vitamin_c' => 0,
                'vitamin_d' => 0,
            ],
            'tahu' => [
                'calories' => 76,
                'protein' => 8,
                'carbohydrates' => 1.9,
                'fat' => 4.8,
                'fiber' => 0.3,
                'calcium' => 350,
                'iron' => 5.4,
                'zinc' => 0.8,
                'vitamin_a' => 0,
                'vitamin_c' => 0,
                'vitamin_d' => 0,
            ],

            // Carbohydrates
            'nasi' => [
                'calories' => 130,
                'protein' => 2.7,
                'carbohydrates' => 28,
                'fat' => 0.3,
                'fiber' => 0.4,
                'calcium' => 10,
                'iron' => 0.2,
                'zinc' => 0.5,
                'vitamin_a' => 0,
                'vitamin_c' => 0,
                'vitamin_d' => 0,
            ],
            'roti' => [
                'calories' => 265,
                'protein' => 9,
                'carbohydrates' => 49,
                'fat' => 3.2,
                'fiber' => 2.7,
                'calcium' => 151,
                'iron' => 3.6,
                'zinc' => 0.7,
                'vitamin_a' => 0,
                'vitamin_c' => 0,
                'vitamin_d' => 0,
            ],
            'kentang' => [
                'calories' => 77,
                'protein' => 2,
                'carbohydrates' => 17,
                'fat' => 0.1,
                'fiber' => 2.2,
                'calcium' => 12,
                'iron' => 0.8,
                'zinc' => 0.3,
                'vitamin_a' => 2,
                'vitamin_c' => 20,
                'vitamin_d' => 0,
            ],

            // Vegetables
            'bayam' => [
                'calories' => 23,
                'protein' => 2.9,
                'carbohydrates' => 3.6,
                'fat' => 0.4,
                'fiber' => 2.2,
                'calcium' => 99,
                'iron' => 2.7,
                'zinc' => 0.5,
                'vitamin_a' => 469,
                'vitamin_c' => 28,
                'vitamin_d' => 0,
            ],
            'wortel' => [
                'calories' => 41,
                'protein' => 0.9,
                'carbohydrates' => 10,
                'fat' => 0.2,
                'fiber' => 2.8,
                'calcium' => 33,
                'iron' => 0.3,
                'zinc' => 0.2,
                'vitamin_a' => 835,
                'vitamin_c' => 6,
                'vitamin_d' => 0,
            ],
            'brokoli' => [
                'calories' => 34,
                'protein' => 2.8,
                'carbohydrates' => 7,
                'fat' => 0.4,
                'fiber' => 2.6,
                'calcium' => 47,
                'iron' => 0.7,
                'zinc' => 0.4,
                'vitamin_a' => 31,
                'vitamin_c' => 89,
                'vitamin_d' => 0,
            ],

            // Fruits
            'pisang' => [
                'calories' => 89,
                'protein' => 1.1,
                'carbohydrates' => 23,
                'fat' => 0.3,
                'fiber' => 2.6,
                'calcium' => 5,
                'iron' => 0.3,
                'zinc' => 0.2,
                'vitamin_a' => 3,
                'vitamin_c' => 9,
                'vitamin_d' => 0,
            ],
            'jeruk' => [
                'calories' => 47,
                'protein' => 0.9,
                'carbohydrates' => 12,
                'fat' => 0.1,
                'fiber' => 2.4,
                'calcium' => 40,
                'iron' => 0.1,
                'zinc' => 0.1,
                'vitamin_a' => 11,
                'vitamin_c' => 53,
                'vitamin_d' => 0,
            ],
            'pepaya' => [
                'calories' => 43,
                'protein' => 0.5,
                'carbohydrates' => 11,
                'fat' => 0.3,
                'fiber' => 1.7,
                'calcium' => 20,
                'iron' => 0.3,
                'zinc' => 0.1,
                'vitamin_a' => 47,
                'vitamin_c' => 61,
                'vitamin_d' => 0,
            ],

            // Dairy
            'susu' => [
                'calories' => 61,
                'protein' => 3.2,
                'carbohydrates' => 4.8,
                'fat' => 3.3,
                'fiber' => 0,
                'calcium' => 113,
                'iron' => 0,
                'zinc' => 0.4,
                'vitamin_a' => 46,
                'vitamin_c' => 0,
                'vitamin_d' => 1.3,
            ],
            'yogurt' => [
                'calories' => 59,
                'protein' => 3.5,
                'carbohydrates' => 4.7,
                'fat' => 3.3,
                'fiber' => 0,
                'calcium' => 121,
                'iron' => 0.1,
                'zinc' => 0.6,
                'vitamin_a' => 27,
                'vitamin_c' => 0.5,
                'vitamin_d' => 0,
            ],
        ];
    }

    /**
     * Search foods by name
     */
    public function searchFoods(string $query): array
    {
        $database = $this->getFoodDatabase();
        $query = strtolower($query);

        $results = [];
        foreach ($database as $name => $nutrition) {
            if (strpos($name, $query) !== false) {
                $results[] = [
                    'name' => ucfirst($name),
                    'key' => $name,
                    'nutrition_per_100g' => $nutrition,
                ];
            }
        }

        return $results;
    }
}
