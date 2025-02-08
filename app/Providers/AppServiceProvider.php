<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Domain;
use Illuminate\Support\ServiceProvider;
use App\Models\TenantApp;
use App\Observers\TenantAppObserver;
use App\Models\Tenant;
use App\Observers\ApplicationObserver;
use App\Observers\DomainObserver;
use App\Observers\TenantObserver;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;

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
        TenantApp::observe(TenantAppObserver::class);
        Tenant::observe(TenantObserver::class);
        Domain::observe(DomainObserver::class);
        Application::observe(ApplicationObserver::class);

        Filament::serving(function () {
            // Using Vite
            Filament::registerViteTheme('resources/css/filament.css');

        });

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);
        FilamentAsset::register([

            Css::make('tippy-external-stylesheet', 'https://unpkg.com/tippy.js@6/dist/tippy.css'),
            // Css::make('example-local-stylesheet', asset('css/local.css')),
            // Js::make('custom-script', __DIR__ . '/../../resources/js/my-script.js'),
            Js::make('alpine-external-script', 'https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@0.x.x/dist/cdn.min.js'),
        ]);
    }
}
