<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Illuminate\Support\ServiceProvider;
use Lanin\Laravel\ApiExceptions\Support\Validator;

class ApiExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set custom validation resolver
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $attributes) {
            return new Validator($translator, $data, $rules, $messages, $attributes);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
