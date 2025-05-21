<?php

namespace Cknow\Money;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
        $this->mergeConfigFrom(__DIR__.'/../config/iso-currencies.php', 'iso-currencies');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/money.php' => config_path('money.php')], 'config');
            $this->publishes([__DIR__.'/../config/iso-currencies.php' => config_path('iso-currencies.php')], 'config');
        }

        $this->callAfterResolving(BladeCompiler::class, function ($blade) {
            BladeExtension::register($blade);
        });

        Validator::extend('currency', function ($attribute, $value) {
            $rule = new Rules\Currency;

            return $rule->passes($attribute, $value);
        });

        Validator::extend('money', function ($attribute, $value, $parameters) {
            $rule = new Rules\Money(...$parameters);

            return $rule->passes($attribute, $value);
        });
    }
}
