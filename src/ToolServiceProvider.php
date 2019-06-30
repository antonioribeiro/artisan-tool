<?php

namespace PragmaRX\ArtisanTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PragmaRX\ArtisanTool\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'artisan-tool');

        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishAndMergeConfig();

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    private function publishAndMergeConfig(): void
    {
        $this->publishes(
            [
                ($config =
                    __DIR__ . '/../config/artisan-tool.php') => config_path(
                    'artisan-tool.php'
                )
            ],
            'artisan-tool'
        );

        $this->mergeConfigFrom($config, 'artisan-tool');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/pragmarx/artisan-tool')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
