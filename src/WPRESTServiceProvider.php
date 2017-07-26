<?php

namespace Tradzero\WPREST;

use Illuminate\Support\ServiceProvider;

class WPRESTServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind('wprest', function ($app) {
            
            $sender = new Send();

            return $sender;
        });
    }
}
