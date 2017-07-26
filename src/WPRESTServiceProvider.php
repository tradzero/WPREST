<?php

namespace Tradzero\WPREST;

use Illuminate\Support\ServiceProvider;

class WPRESTServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/wordpress.php' => config_path('wordpress.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('wprest', function ($app) {
            
            $sender = new Send();

            return $sender;
        });
    }
}
