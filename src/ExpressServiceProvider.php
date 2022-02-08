<?php
namespace Walkerdistance\LaravelExpress;

use Illuminate\Support\ServiceProvider;

class ExpressServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ExpressInquiry::class, function () {
            return new ExpressInquiry(config('express.express_hundred'));
        });
        $this->app->alias(ExpressInquiry::class, 'expressInquiry');
    }

    public function provides()
    {
        return [ExpressInquiry::class, 'expressInquiry'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/express.php' => config_path('express.php'),
        ],'express');
    }
}