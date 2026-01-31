<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\NutritionRecord;
use App\Services\NutritionCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class NutritionRecordController extends Controller
{
    private NutritionCalculatorService $nutritionService;

    public function __construct(NutritionCalculatorService $nutritionService)
    {
        $this->nutritionService = $nutritionService;
    }

    /**
     * Display nutrition records for a child
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

        $query = $child->nutritionRecords();

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('meal_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('meal_date', '<=', $request->end_date);
        }

        $records = $query->orderBy('meal_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

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
     * Store a new nutrition record
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
            'meal_date' => 'required|date|before_or_equal:today',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:255',
            'portion_size' => 'required|numeric|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Calculate nutrition from food database
        $nutrition = $this->nutritionService->calculateNutritionFromFood(
            $request->food_name,
            $request->portion_size
        );

        // Create nutrition record
        $record = NutritionRecord::create([
            'child_id' => $child->id,
            'meal_date' => $request->meal_date,
            'meal_type' => $request->meal_type,
            'food_name' => $request->food_name,
            'portion_size' => $request->portion_size,
            'calories' => $nutrition['calories'],
            'protein' => $nutrition['protein'],
            'carbohydrates' => $nutrition['carbohydrates'],
            'fat' => $nutrition['fat'],
            'fiber' => $nutrition['fiber'],
            'calcium' => $nutrition['calcium'],
            'iron' => $nutrition['iron'],
            'zinc' => $nutrition['zinc'],
            'vitamin_a' => $nutrition['vitamin_a'],
            'vitamin_c' => $nutrition['vitamin_c'],
            'vitamin_d' => $nutrition['vitamin_d'],
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data nutrisi berhasil disimpan',
            'data' => $record,
        ], 201);
    }

    /**
     * Store nutrition with custom values
     */
    public function storeCustom(Request $request, $childId)
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
            'meal_date' => 'required|date|before_or_equal:today',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:255',
            'portion_size' => 'required|numeric|min:1',
            'calories' => 'required|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'carbohydrates' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calcium' => 'nullable|numeric|min:0',
            'iron' => 'nullable|numeric|min:0',
            'zinc' => 'nullable|numeric|min:0',
            'vitamin_a' => 'nullable|numeric|min:0',
            'vitamin_c' => 'nullable|numeric|min:0',
            'vitamin_d' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = NutritionRecord::create($request->all() + ['child_id' => $child->id]);

        return response()->json([
            'success' => true,
            'message' => 'Data nutrisi kustom berhasil disimpan',
            'data' => $record,
        ], 201);
    }

    /**
     * Get daily nutrition summary
     */
    public function dailySummary(Request $request, $childId)
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

        $date = $request->query('date', now()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);

        $summary = $this->nutritionService->getDailySummary($child, $carbonDate);

        return response()->json([
            'success' => true,
            'data' => [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'age_in_months' => $child->age_in_months,
                ],
                'summary' => $summary,
            ],
        ]);
    }

    /**
     * Get nutritional needs
     */
    public function nutritionalNeeds(Request $request, $childId)
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

        $needs = $this->nutritionService->getDailyNutritionalNeeds($child->age_in_months);

        return response()->json([
            'success' => true,
            'data' => [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'age_in_months' => $child->age_in_months,
                ],
                'daily_nutritional_needs' => $needs,
            ],
        ]);
    }

    /**
     * Get food recommendations based on deficiencies
     */
    public function recommendations(Request $request, $childId)
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

        $date = $request->query('date', now()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);

        $summary = $this->nutritionService->getDailySummary($child, $carbonDate);

        $deficientNutrients = array_merge(
            $summary['assessment']['critical_nutrients'] ?? [],
            $summary['assessment']['warning_nutrients'] ?? []
        );

        $recommendations = $this->nutritionService->getFoodRecommendations($deficientNutrients);

        return response()->json([
            'success' => true,
            'data' => [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                ],
                'date' => $date,
                'deficient_nutrients' => $deficientNutrients,
                'food_recommendations' => $recommendations,
                'assessment' => $summary['assessment'],
            ],
        ]);
    }

    /**
     * Search foods in database
     */
    public function searchFoods(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query minimal 2 karakter',
            ], 400);
        }

        $results = $this->nutritionService->searchFoods($query);

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Delete a nutrition record
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

        $record = $child->nutritionRecords()->find($recordId);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Data nutrisi tidak ditemukan',
            ], 404);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data nutrisi berhasil dihapus',
        ]);
    }
}
