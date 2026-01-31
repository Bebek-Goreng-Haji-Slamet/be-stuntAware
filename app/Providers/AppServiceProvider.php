<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WHOGrowthStandardsService;
use App\Services\GrowthAnalysisService;
use App\Services\NutritionCalculatorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register WHO Growth Standards Service as Singleton
        $this->app->singleton(WHOGrowthStandardsService::class, function ($app) {
            return new WHOGrowthStandardsService();
        });

        // Register Growth Analysis Service
        $this->app->singleton(GrowthAnalysisService::class, function ($app) {
            return new GrowthAnalysisService(
                $app->make(WHOGrowthStandardsService::class)
            );
        });

        // Register Nutrition Calculator Service
        $this->app->singleton(NutritionCalculatorService::class, function ($app) {
            return new NutritionCalculatorService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
