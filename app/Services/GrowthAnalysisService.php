<?php

namespace App\Services;

use App\Models\Child;
use App\Models\GrowthRecord;

class GrowthAnalysisService
{
    private WHOGrowthStandardsService $whoService;

    public function __construct(WHOGrowthStandardsService $whoService)
    {
        $this->whoService = $whoService;
    }

    /**
     * Analyze growth data and detect stunting
     */
    public function analyzeGrowth(Child $child, float $weight, float $height, string $measurementDate): array
    {
        $ageInMonths = $child->birth_date->diffInMonths($measurementDate);

        // Calculate Z-scores using WHO standards
        $zScores = $this->whoService->calculateZScores(
            $ageInMonths,
            $weight,
            $height,
            $child->gender
        );

        // Classify status
        $stuntingStatus = $this->whoService->classifyStuntingStatus($zScores['height_for_age_zscore']);
        $wastingStatus = $this->whoService->classifyWastingStatus($zScores['weight_for_height_zscore']);
        $underweightStatus = $this->whoService->classifyUnderweightStatus($zScores['weight_for_age_zscore']);

        // Generate AI analysis
        $analysis = $this->generateAnalysis(
            $child,
            $ageInMonths,
            $zScores,
            $stuntingStatus,
            $wastingStatus,
            $underweightStatus
        );

        // Generate recommendations
        $recommendations = $this->generateRecommendations(
            $child,
            $stuntingStatus,
            $wastingStatus,
            $underweightStatus,
            $zScores
        );

        return [
            'age_in_months' => $ageInMonths,
            'weight_for_age_zscore' => $zScores['weight_for_age_zscore'],
            'height_for_age_zscore' => $zScores['height_for_age_zscore'],
            'weight_for_height_zscore' => $zScores['weight_for_height_zscore'],
            'stunting_status' => $stuntingStatus,
            'wasting_status' => $wastingStatus,
            'underweight_status' => $underweightStatus,
            'ai_analysis' => $analysis,
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Generate detailed analysis text
     */
    private function generateAnalysis(
        Child $child,
        int $ageInMonths,
        array $zScores,
        string $stuntingStatus,
        string $wastingStatus,
        string $underweightStatus
    ): string {
        $analysis = [];

        // Overall assessment
        $analysis[] = "Analisis Pertumbuhan untuk {$child->name} (Usia: {$ageInMonths} bulan)";
        $analysis[] = "";

        // Stunting analysis
        $analysis[] = "TINGGI BADAN (Height-for-Age):";
        $analysis[] = "Z-score: {$zScores['height_for_age_zscore']}";
        $analysis[] = $this->getStuntingExplanation($stuntingStatus, $zScores['height_for_age_zscore']);
        $analysis[] = "";

        // Wasting analysis
        $analysis[] = "BERAT BADAN TERHADAP TINGGI (Weight-for-Height):";
        $analysis[] = "Z-score: {$zScores['weight_for_height_zscore']}";
        $analysis[] = $this->getWastingExplanation($wastingStatus, $zScores['weight_for_height_zscore']);
        $analysis[] = "";

        // Underweight analysis
        $analysis[] = "BERAT BADAN (Weight-for-Age):";
        $analysis[] = "Z-score: {$zScores['weight_for_age_zscore']}";
        $analysis[] = $this->getUnderweightExplanation($underweightStatus, $zScores['weight_for_age_zscore']);

        return implode("\n", $analysis);
    }

    private function getStuntingExplanation(string $status, float $zScore): string
    {
        switch ($status) {
            case 'normal':
                return "✓ Tinggi badan anak normal sesuai usianya. Pertahankan asupan gizi yang baik.";
            case 'at_risk':
                return "⚠ Anak berisiko stunting. Tinggi badan di bawah normal. Perlu peningkatan asupan gizi.";
            case 'stunted':
                return "⚠ Anak mengalami stunting (pendek). Konsultasi dengan tenaga kesehatan diperlukan.";
            case 'severely_stunted':
                return "⚠ PERHATIAN: Anak mengalami stunting berat. Segera konsultasi dengan dokter atau ahli gizi.";
            default:
                return "Status tidak dapat ditentukan.";
        }
    }

    private function getWastingExplanation(string $status, float $zScore): string
    {
        switch ($status) {
            case 'normal':
                return "✓ Berat badan sesuai dengan tinggi badan. Kondisi gizi akut baik.";
            case 'wasted':
                return "⚠ Anak mengalami wasting (kurus). Perlu peningkatan kalori dan protein.";
            case 'severely_wasted':
                return "⚠ PERHATIAN: Anak mengalami wasting berat. Segera konsultasi dengan tenaga kesehatan.";
            default:
                return "Status tidak dapat ditentukan.";
        }
    }

    private function getUnderweightExplanation(string $status, float $zScore): string
    {
        switch ($status) {
            case 'normal':
                return "✓ Berat badan sesuai usia. Pertumbuhan baik.";
            case 'underweight':
                return "⚠ Berat badan kurang. Tingkatkan asupan makanan bergizi.";
            case 'severely_underweight':
                return "⚠ PERHATIAN: Berat badan sangat kurang. Konsultasi medis diperlukan segera.";
            default:
                return "Status tidak dapat ditentukan.";
        }
    }

    /**
     * Generate personalized recommendations
     */
    private function generateRecommendations(
        Child $child,
        string $stuntingStatus,
        string $wastingStatus,
        string $underweightStatus,
        array $zScores
    ): string {
        $recommendations = [];

        // Check if intervention needed
        $needsIntervention = !in_array($stuntingStatus, ['normal']) ||
            !in_array($wastingStatus, ['normal']) ||
            !in_array($underweightStatus, ['normal']);

        if (!$needsIntervention) {
            return "Pertumbuhan anak Anda sangat baik! Teruskan pola asuh dan pemberian nutrisi yang seimbang. Lakukan pemeriksaan rutin setiap bulan untuk memantau pertumbuhan.";
        }

        $recommendations[] = "REKOMENDASI TINDAKAN:";
        $recommendations[] = "";

        // Stunting-specific recommendations
        if ($stuntingStatus !== 'normal') {
            $recommendations[] = "Untuk Mengatasi Stunting:";
            $recommendations[] = "• Berikan makanan tinggi protein (telur, ikan, daging, tempe, tahu)";
            $recommendations[] = "• Pastikan asupan kalsium cukup (susu, keju, yogurt)";
            $recommendations[] = "• Berikan zinc dari makanan (daging merah, kacang-kacangan)";
            $recommendations[] = "• Konsumsi vitamin D (sinar matahari pagi, ikan)";
            $recommendations[] = "";
        }

        // Wasting-specific recommendations
        if ($wastingStatus !== 'normal') {
            $recommendations[] = "Untuk Mengatasi Kekurusan:";
            $recommendations[] = "• Tingkatkan frekuensi makan (5-6 kali sehari)";
            $recommendations[] = "• Berikan makanan padat kalori (alpukat, selai kacang, minyak kelapa)";
            $recommendations[] = "• Tambahkan lemak sehat dalam makanan";
            $recommendations[] = "• Berikan camilan bergizi di antara waktu makan";
            $recommendations[] = "";
        }

        // General recommendations
        $recommendations[] = "Rekomendasi Umum:";
        $recommendations[] = "• Konsultasi dengan dokter anak atau ahli gizi";
        $recommendations[] = "• Berikan ASI eksklusif (jika < 6 bulan)";
        $recommendations[] = "• Berikan MPASI sesuai usia (jika ≥ 6 bulan)";
        $recommendations[] = "• Jaga kebersihan makanan dan lingkungan";
        $recommendations[] = "• Pantau pertumbuhan setiap bulan di Posyandu";
        $recommendations[] = "• Lengkapi imunisasi sesuai jadwal";

        // Severe cases
        if (
            $stuntingStatus === 'severely_stunted' ||
            $wastingStatus === 'severely_wasted' ||
            $underweightStatus === 'severely_underweight'
        ) {
            $recommendations[] = "";
            $recommendations[] = "⚠ PENTING: Kondisi anak memerlukan penanganan medis segera.";
            $recommendations[] = "Segera hubungi dokter atau kunjungi fasilitas kesehatan terdekat.";
        }

        return implode("\n", $recommendations);
    }

    /**
     * Get growth trend from historical data
     */
    public function getGrowthTrend(Child $child, int $months = 6): array
    {
        $records = $child->growthRecords()
            ->where('measurement_date', '>=', now()->subMonths($months))
            ->orderBy('measurement_date', 'asc')
            ->get();

        return [
            'trend_data' => $records->map(function ($record) {
                return [
                    'date' => $record->measurement_date->format('Y-m-d'),
                    'age_months' => $record->age_in_months,
                    'height' => $record->height,
                    'weight' => $record->weight,
                    'height_zscore' => $record->height_for_age_zscore,
                    'status' => $record->stunting_status,
                ];
            }),
            'trend_analysis' => $this->analyzeTrend($records),
        ];
    }

    private function analyzeTrend($records): string
    {
        if ($records->count() < 2) {
            return "Data tidak cukup untuk analisis tren. Lakukan pengukuran rutin setiap bulan.";
        }

        $first = $records->first();
        $last = $records->last();

        $heightGrowth = $last->height - $first->height;
        $weightGrowth = $last->weight - $first->weight;
        $monthsDiff = $first->measurement_date->diffInMonths($last->measurement_date);

        $analysis = [];
        $analysis[] = "Tren Pertumbuhan ({$monthsDiff} bulan terakhir):";
        $analysis[] = "• Pertumbuhan tinggi: +{$heightGrowth} cm";
        $analysis[] = "• Pertambahan berat: +{$weightGrowth} kg";

        // Check if improvement
        if ($last->height_for_age_zscore > $first->height_for_age_zscore) {
            $analysis[] = "• Tren positif: Z-score tinggi meningkat ✓";
        } else {
            $analysis[] = "• Perhatian: Z-score tinggi menurun atau stagnan";
        }

        return implode("\n", $analysis);
    }
}
