<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Throwable;

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
        View::composer('*', function ($view) {
            $theme = 'auto';

            $nativeSettingsFacade = 'Native\\Laravel\\Facades\\Settings';
            if (class_exists($nativeSettingsFacade) && app()->bound('native.settings')) {
                try {
                    $theme = $nativeSettingsFacade::get('theme', 'auto');
                } catch (Throwable $exception) {
                    $theme = Cache::get('settings.theme', 'auto');
                }
            } else {
                $theme = Cache::get('settings.theme', 'auto');
            }

            if (!in_array($theme, ['light', 'dark', 'auto'], true)) {
                $theme = 'auto';
            }

            $view->with('appTheme', $theme);
        });
    }
}
