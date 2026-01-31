<?php

namespace App\Services;

/**
 * WHO Growth Standards Service
 * Implements WHO Child Growth Standards for stunting detection
 * Based on WHO 2006 standards for children 0-60 months
 */
class WHOGrowthStandardsService
{
    /**
     * Calculate Z-score using LMS method
     * Z = ((value/M)^L - 1) / (L * S)
     */
    private function calculateZScore(float $value, float $L, float $M, float $S): float
    {
        if ($L == 0) {
            return log($value / $M) / $S;
        }
        return (pow($value / $M, $L) - 1) / ($L * $S);
    }

    /**
     * Get LMS parameters for Height-for-Age
     * Simplified version - in production, use complete WHO tables
     */
    private function getHeightForAgeLMS(int $ageInMonths, string $gender): array
    {
        // Simplified LMS values (subset of WHO data)
        // In production, load from complete WHO tables
        $data = [
            'male' => [
                0 => ['L' => 0.3487, 'M' => 49.8842, 'S' => 0.03795],
                6 => ['L' => 0.3809, 'M' => 67.6236, 'S' => 0.04178],
                12 => ['L' => 0.3986, 'M' => 75.7488, 'S' => 0.04336],
                24 => ['L' => 0.3968, 'M' => 87.0768, 'S' => 0.04336],
                36 => ['L' => 0.3480, 'M' => 96.1260, 'S' => 0.04362],
                48 => ['L' => 0.2986, 'M' => 103.3182, 'S' => 0.04446],
                60 => ['L' => 0.2575, 'M' => 109.2212, 'S' => 0.04541],
            ],
            'female' => [
                0 => ['L' => 0.3809, 'M' => 49.1477, 'S' => 0.0379],
                6 => ['L' => 0.4182, 'M' => 65.7311, 'S' => 0.04202],
                12 => ['L' => 0.4366, 'M' => 74.0598, 'S' => 0.04443],
                24 => ['L' => 0.4370, 'M' => 85.7289, 'S' => 0.04574],
                36 => ['L' => 0.3809, 'M' => 95.1395, 'S' => 0.04640],
                48 => ['L' => 0.3292, 'M' => 102.3715, 'S' => 0.04744],
                60 => ['L' => 0.2865, 'M' => 108.3693, 'S' => 0.04857],
            ],
        ];

        return $this->interpolateLMS($data[$gender], $ageInMonths);
    }

    /**
     * Get LMS parameters for Weight-for-Age
     */
    private function getWeightForAgeLMS(int $ageInMonths, string $gender): array
    {
        $data = [
            'male' => [
                0 => ['L' => 0.3487, 'M' => 3.3464, 'S' => 0.14602],
                6 => ['L' => 0.2247, 'M' => 7.9340, 'S' => 0.12385],
                12 => ['L' => 0.1738, 'M' => 9.6479, 'S' => 0.11727],
                24 => ['L' => 0.1915, 'M' => 12.2315, 'S' => 0.11316],
                36 => ['L' => 0.2133, 'M' => 14.3065, 'S' => 0.11245],
                48 => ['L' => 0.2297, 'M' => 16.3362, 'S' => 0.11428],
                60 => ['L' => 0.2417, 'M' => 18.3170, 'S' => 0.11776],
            ],
            'female' => [
                0 => ['L' => 0.3809, 'M' => 3.2322, 'S' => 0.14171],
                6 => ['L' => 0.2315, 'M' => 7.2115, 'S' => 0.12619],
                12 => ['L' => 0.1395, 'M' => 8.9481, 'S' => 0.12204],
                24 => ['L' => 0.1465, 'M' => 11.4908, 'S' => 0.11960],
                36 => ['L' => 0.1639, 'M' => 13.5700, 'S' => 0.12053],
                48 => ['L' => 0.1755, 'M' => 15.5446, 'S' => 0.12379],
                60 => ['L' => 0.1849, 'M' => 17.3925, 'S' => 0.12857],
            ],
        ];

        return $this->interpolateLMS($data[$gender], $ageInMonths);
    }

    /**
     * Get LMS parameters for Weight-for-Height
     */
    private function getWeightForHeightLMS(float $height, string $gender): array
    {
        // Simplified - use height ranges
        // In production, use complete WHO weight-for-length/height tables
        $data = [
            'male' => [
                50 => ['L' => 0.3487, 'M' => 3.4, 'S' => 0.14602],
                70 => ['L' => 0.1, 'M' => 8.6, 'S' => 0.13],
                90 => ['L' => 0.15, 'M' => 13.0, 'S' => 0.12],
                110 => ['L' => 0.2, 'M' => 18.5, 'S' => 0.13],
            ],
            'female' => [
                50 => ['L' => 0.3809, 'M' => 3.3, 'S' => 0.14171],
                70 => ['L' => 0.1, 'M' => 8.2, 'S' => 0.13],
                90 => ['L' => 0.15, 'M' => 12.5, 'S' => 0.12],
                110 => ['L' => 0.2, 'M' => 17.8, 'S' => 0.13],
            ],
        ];

        return $this->interpolateLMS($data[$gender], $height);
    }

    /**
     * Interpolate LMS values between reference points
     */
    private function interpolateLMS(array $referenceData, float $value): array
    {
        $keys = array_keys($referenceData);

        // Find surrounding values
        $lower = null;
        $upper = null;

        foreach ($keys as $key) {
            if ($key <= $value) {
                $lower = $key;
            }
            if ($key >= $value && $upper === null) {
                $upper = $key;
            }
        }

        // If exact match
        if ($lower === $value) {
            return $referenceData[$lower];
        }

        // If out of range, use nearest
        if ($lower === null) {
            return $referenceData[$keys[0]];
        }
        if ($upper === null) {
            return $referenceData[$keys[count($keys) - 1]];
        }

        // Linear interpolation
        $fraction = ($value - $lower) / ($upper - $lower);

        return [
            'L' => $referenceData[$lower]['L'] +
                ($referenceData[$upper]['L'] - $referenceData[$lower]['L']) * $fraction,
            'M' => $referenceData[$lower]['M'] +
                ($referenceData[$upper]['M'] - $referenceData[$lower]['M']) * $fraction,
            'S' => $referenceData[$lower]['S'] +
                ($referenceData[$upper]['S'] - $referenceData[$lower]['S']) * $fraction,
        ];
    }

    /**
     * Calculate all Z-scores for a growth measurement
     */
    public function calculateZScores(int $ageInMonths, float $weight, float $height, string $gender): array
    {
        $gender = strtolower($gender);

        // Height-for-age Z-score (stunting indicator)
        $hfaLMS = $this->getHeightForAgeLMS($ageInMonths, $gender);
        $heightForAgeZScore = $this->calculateZScore(
            $height,
            $hfaLMS['L'],
            $hfaLMS['M'],
            $hfaLMS['S']
        );

        // Weight-for-age Z-score (underweight indicator)
        $wfaLMS = $this->getWeightForAgeLMS($ageInMonths, $gender);
        $weightForAgeZScore = $this->calculateZScore(
            $weight,
            $wfaLMS['L'],
            $wfaLMS['M'],
            $wfaLMS['S']
        );

        // Weight-for-height Z-score (wasting indicator)
        $wfhLMS = $this->getWeightForHeightLMS($height, $gender);
        $weightForHeightZScore = $this->calculateZScore(
            $weight,
            $wfhLMS['L'],
            $wfhLMS['M'],
            $wfhLMS['S']
        );

        return [
            'height_for_age_zscore' => round($heightForAgeZScore, 2),
            'weight_for_age_zscore' => round($weightForAgeZScore, 2),
            'weight_for_height_zscore' => round($weightForHeightZScore, 2),
        ];
    }

    /**
     * Classify stunting status based on height-for-age Z-score
     */
    public function classifyStuntingStatus(float $zScore): string
    {
        if ($zScore >= -1) {
            return 'normal';
        } elseif ($zScore >= -2) {
            return 'at_risk';
        } elseif ($zScore >= -3) {
            return 'stunted';
        } else {
            return 'severely_stunted';
        }
    }

    /**
     * Classify wasting status based on weight-for-height Z-score
     */
    public function classifyWastingStatus(float $zScore): string
    {
        if ($zScore >= -2) {
            return 'normal';
        } elseif ($zScore >= -3) {
            return 'wasted';
        } else {
            return 'severely_wasted';
        }
    }

    /**
     * Classify underweight status based on weight-for-age Z-score
     */
    public function classifyUnderweightStatus(float $zScore): string
    {
        if ($zScore >= -2) {
            return 'normal';
        } elseif ($zScore >= -3) {
            return 'underweight';
        } else {
            return 'severely_underweight';
        }
    }
}
