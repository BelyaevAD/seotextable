<?php

namespace Belyaevad\SeoTextable;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class SeoTextableServiceProvider extends BaseServiceProvider
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
        $source = dirname(__DIR__).'/resources/config/seotextable.php';

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('seotextable.php')],
                'config');
        }

        $this->mergeConfigFrom($source, 'seotextable');

        if ( ! class_exists('CreateSeoTextableTable')) {
            $this->publishes([
                __DIR__
                .'/../resources/database/migrations/create_seo_textable_table.php.stub' => database_path('migrations/'
                    .date('Y_m_d_His').'_create_seo_textable_table.php'),
            ], 'migrations');
        }

        $this->loadMigrationsFrom(
            __DIR__.'/../resources/database/migrations'
        );

        include __DIR__.'/routes.php';
    }
}
