<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrowthRecordController;
use App\Http\Controllers\NutritionRecordController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'service' => 'Stunting Prevention API',
    ]);
});

// Auth routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes (protected)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Children management
    Route::prefix('children')->group(function () {
        Route::get('/', [ChildController::class, 'index']);
        Route::post('/', [ChildController::class, 'store']);
        Route::get('/{id}', [ChildController::class, 'show']);
        Route::put('/{id}', [ChildController::class, 'update']);
        Route::delete('/{id}', [ChildController::class, 'destroy']);

        // Growth records for specific child
        Route::prefix('{childId}/growth')->group(function () {
            Route::get('/', [GrowthRecordController::class, 'index']);
            Route::post('/', [GrowthRecordController::class, 'store']);
            Route::get('/trend', [GrowthRecordController::class, 'trend']);
            Route::get('/{recordId}', [GrowthRecordController::class, 'show']);
            Route::delete('/{recordId}', [GrowthRecordController::class, 'destroy']);
        });

        // Nutrition records for specific child
        Route::prefix('{childId}/nutrition')->group(function () {
            Route::get('/', [NutritionRecordController::class, 'index']);
            Route::post('/', [NutritionRecordController::class, 'store']);
            Route::post('/custom', [NutritionRecordController::class, 'storeCustom']);
            Route::get('/summary', [NutritionRecordController::class, 'dailySummary']);
            Route::get('/needs', [NutritionRecordController::class, 'nutritionalNeeds']);
            Route::get('/recommendations', [NutritionRecordController::class, 'recommendations']);
            Route::delete('/{recordId}', [NutritionRecordController::class, 'destroy']);
        });
    });

    // Food database search (global)
    Route::get('/foods/search', [NutritionRecordController::class, 'searchFoods']);
});
