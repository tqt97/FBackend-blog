<?php

namespace App\Providers;

use App\Helpers\SEOMeta;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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
        // Model::preventLazyLoading(! app()->isProduction());
        // Model::unguard();
        $this->app->singleton('seometa', function ($app) {
            return new SEOMeta(new Config($app->config->get('blog.seo.meta')));
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['vi','en'])
                ->circular();
        });
    }
}
