<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChildController extends Controller
{
    /**
     * Display a listing of children for authenticated user
     */
    public function index(Request $request)
    {
        $children = Child::where('user_id', $request->user()->id)
            ->with(['growthRecords' => function ($query) {
                $query->latest('measurement_date')->limit(1);
            }])
            ->get()
            ->map(function ($child) {
                $latestRecord = $child->growthRecords->first();

                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'gender' => $child->gender,
                    'birth_date' => $child->birth_date->format('Y-m-d'),
                    'age_in_months' => $child->age_in_months,
                    'age_in_years' => $child->age_in_years,
                    'birth_weight' => $child->birth_weight,
                    'birth_height' => $child->birth_height,
                    'latest_status' => $latestRecord ? [
                        'measurement_date' => $latestRecord->measurement_date->format('Y-m-d'),
                        'weight' => $latestRecord->weight,
                        'height' => $latestRecord->height,
                        'stunting_status' => $latestRecord->stunting_status,
                    ] : null,
                    'created_at' => $child->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $children,
        ]);
    }

    /**
     * Store a newly created child
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before:today',
            'birth_weight' => 'nullable|numeric|min:0|max:10',
            'birth_height' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $child = Child::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'birth_weight' => $request->birth_weight,
            'birth_height' => $request->birth_height,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anak berhasil ditambahkan',
            'data' => [
                'id' => $child->id,
                'name' => $child->name,
                'gender' => $child->gender,
                'birth_date' => $child->birth_date->format('Y-m-d'),
                'age_in_months' => $child->age_in_months,
            ],
        ], 201);
    }

    /**
     * Display the specified child
     */
    public function show(Request $request, $id)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->with(['growthRecords' => function ($query) {
                $query->orderBy('measurement_date', 'desc')->limit(10);
            }])
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $child->id,
                'name' => $child->name,
                'gender' => $child->gender,
                'birth_date' => $child->birth_date->format('Y-m-d'),
                'age_in_months' => $child->age_in_months,
                'age_in_years' => $child->age_in_years,
                'birth_weight' => $child->birth_weight,
                'birth_height' => $child->birth_height,
                'growth_history' => $child->growthRecords->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'measurement_date' => $record->measurement_date->format('Y-m-d'),
                        'age_in_months' => $record->age_in_months,
                        'weight' => $record->weight,
                        'height' => $record->height,
                        'stunting_status' => $record->stunting_status,
                        'height_for_age_zscore' => $record->height_for_age_zscore,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update the specified child
     */
    public function update(Request $request, $id)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'birth_date' => 'sometimes|required|date|before:today',
            'birth_weight' => 'nullable|numeric|min:0|max:10',
            'birth_height' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $child->update($request->only([
            'name',
            'gender',
            'birth_date',
            'birth_weight',
            'birth_height'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil diperbarui',
            'data' => $child,
        ]);
    }

    /**
     * Remove the specified child
     */
    public function destroy(Request $request, $id)
    {
        $child = Child::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$child) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }

        $child->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data anak berhasil dihapus',
        ]);
    }
}
