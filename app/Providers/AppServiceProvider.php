<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (
            app()->environment('production') ||
            str_contains(request()->getHost(), 'railway.app') ||
            str_contains(request()->header('x-forwarded-proto', ''), 'https') ||
            str_starts_with(config('app.url'), 'https://')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto seed rooms if database is empty or room images are missing on deployment
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('rooms')) {
                if (\App\Models\Room::count() === 0 || \App\Models\Room::whereNull('image_url')->exists()) {
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                }
            }
        } catch (\Throwable $e) {
            // Log or ignore if DB is connecting during setup
        }
    }
}
