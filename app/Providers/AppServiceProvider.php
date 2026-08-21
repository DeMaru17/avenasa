<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\CompanyProfile;
use App\Services\LocalizationService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LocalizationService::class, fn () => new LocalizationService);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::defaults(['locale' => app()->getLocale() ?: 'id']);

        View::composer(['layouts.public', 'components.header', 'components.footer', 'errors.*'], function ($view) {
            $view->with('localization', app(LocalizationService::class));

            // Safely share CompanyProfile and Brands for layout if database tables exist
            if (Schema::hasTable('company_profiles')) {
                $view->with('companyProfile', CompanyProfile::first());
            }

            if (Schema::hasTable('brands')) {
                $view->with('principals', Brand::active()->ordered()->take(6)->get());
            }
        });
    }
}
