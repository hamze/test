<?php

namespace App\Providers;

use App\Services\Kavenegar\Client;
use Illuminate\Support\ServiceProvider;

class KavenegarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->singleton(Client::class, function ($app) {
		    return new Client(
			    config('services.ping-ping.uri'),
                config('services.ping-ping.token')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
