<?php

namespace Belyaevad\SeoTextable;

use Illuminate\Support\ServiceProvider;

class SeoTextableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Belyaevad\SeoTextable\SeoTextableController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/seotextable.php' => config_path('seotextable.php'),
        ], 'config');

        if (!class_exists('CreateSeoTextableTable')) {
            $this->publishes([
                __DIR__.'/../resources/database/migrations/create_seo_textable_table.php.stub' => database_path('migrations/' . date('Y_m_d_His') . '_create_seo_textable_table.php'),
            ], 'migrations');
        }

        $this->loadMigrationsFrom(
            __DIR__.'/../resources/database/migrations'
        );

        include __DIR__.'/routes.php';
    }
}
