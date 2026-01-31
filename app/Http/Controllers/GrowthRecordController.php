<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\GrowthRecord;
use App\Services\GrowthAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrowthRecordController extends Controller
{
    private GrowthAnalysisService $analysisService;

    public function __construct(GrowthAnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
    }

    /**
     * Display growth records for a child
     */
    public function index(Request $request, $childId)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $records = $child->growthRecords()
            ->orderBy('measurement_date', 'desc')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'measurement_date' => $record->measurement_date->format('Y-m-d'),
                    'age_in_months' => $record->age_in_months,
                    'weight' => $record->weight,
                    'height' => $record->height,
                    'head_circumference' => $record->head_circumference,
                    'z_scores' => [
                        'height_for_age' => $record->height_for_age_zscore,
                        'weight_for_age' => $record->weight_for_age_zscore,
                        'weight_for_height' => $record->weight_for_height_zscore,
                    ],
                    'status' => [
                        'stunting' => $record->stunting_status,
                        'wasting' => $record->wasting_status,
                        'underweight' => $record->underweight_status,
                    ],
                    'needs_intervention' => $record->needsIntervention(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'age_in_months' => $child->age_in_months,
                ],
                'records' => $records,
                'total_records' => $records->count(),
            ],
        ]);
    }

    /**
     * Store a new growth record and analyze
     */
    public function store(Request $request, $childId)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'measurement_date' => 'required|date|before_or_equal:today|after_or_equal:' . $child->birth_date->format('Y-m-d'),
            'weight' => 'required|numeric|min:1|max:50',
            'height' => 'required|numeric|min:30|max:200',
            'head_circumference' => 'nullable|numeric|min:20|max:70',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Analyze growth using AI service
        $analysis = $this->analysisService->analyzeGrowth(
            $child,
            $request->weight,
            $request->height,
            $request->measurement_date
        );

        // Create growth record with analysis results
        $record = GrowthRecord::create([
            'child_id' => $child->id,
            'measurement_date' => $request->measurement_date,
            'age_in_months' => $analysis['age_in_months'],
            'weight' => $request->weight,
            'height' => $request->height,
            'head_circumference' => $request->head_circumference,
            'weight_for_age_zscore' => $analysis['weight_for_age_zscore'],
            'height_for_age_zscore' => $analysis['height_for_age_zscore'],
            'weight_for_height_zscore' => $analysis['weight_for_height_zscore'],
            'stunting_status' => $analysis['stunting_status'],
            'wasting_status' => $analysis['wasting_status'],
            'underweight_status' => $analysis['underweight_status'],
            'ai_analysis' => $analysis['ai_analysis'],
            'recommendations' => $analysis['recommendations'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pertumbuhan berhasil disimpan dan dianalisis',
            'data' => [
                'record' => [
                    'id' => $record->id,
                    'measurement_date' => $record->measurement_date->format('Y-m-d'),
                    'age_in_months' => $record->age_in_months,
                    'weight' => $record->weight,
                    'height' => $record->height,
                ],
                'analysis' => [
                    'z_scores' => [
                        'height_for_age' => $record->height_for_age_zscore,
                        'weight_for_age' => $record->weight_for_age_zscore,
                        'weight_for_height' => $record->weight_for_height_zscore,
                    ],
                    'status' => [
                        'stunting' => $record->stunting_status,
                        'wasting' => $record->wasting_status,
                        'underweight' => $record->underweight_status,
                    ],
                    'is_stunted' => $record->isStunted(),
                    'needs_intervention' => $record->needsIntervention(),
                    'detailed_analysis' => $record->ai_analysis,
                    'recommendations' => $record->recommendations,
                ],
            ],
        ], 201);
    }

    /**
     * Display a specific growth record
     */
    public function show(Request $request, $childId, $recordId)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $record = $child->growthRecords()->find($recordId);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Data pertumbuhan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $record->id,
                'measurement_date' => $record->measurement_date->format('Y-m-d'),
                'age_in_months' => $record->age_in_months,
                'weight' => $record->weight,
                'height' => $record->height,
                'head_circumference' => $record->head_circumference,
                'z_scores' => [
                    'height_for_age' => $record->height_for_age_zscore,
                    'weight_for_age' => $record->weight_for_age_zscore,
                    'weight_for_height' => $record->weight_for_height_zscore,
                ],
                'status' => [
                    'stunting' => $record->stunting_status,
                    'wasting' => $record->wasting_status,
                    'underweight' => $record->underweight_status,
                ],
                'analysis' => $record->ai_analysis,
                'recommendations' => $record->recommendations,
                'needs_intervention' => $record->needsIntervention(),
            ],
        ]);
    }

    /**
     * Get growth trend analysis
     */
    public function trend(Request $request, $childId)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $months = $request->query('months', 6);
        $trend = $this->analysisService->getGrowthTrend($child, $months);

        return response()->json([
            'success' => true,
            'data' => [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'age_in_months' => $child->age_in_months,
                ],
                'period_months' => $months,
                'trend_data' => $trend['trend_data'],
                'trend_analysis' => $trend['trend_analysis'],
            ],
        ]);
    }

    /**
     * Delete a growth record
     */
    public function destroy(Request $request, $childId, $recordId)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $childId)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $record = $child->growthRecords()->find($recordId);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Data pertumbuhan tidak ditemukan',
            ], 404);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pertumbuhan berhasil dihapus',
        ]);
    }
}
