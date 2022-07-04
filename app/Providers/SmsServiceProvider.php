<?php

namespace App\Providers;

use App\Services\KavenegarService;
use App\Services\SmsServiceInterface;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->bind(SmsServiceInterface::class, function ($app) {
		    return new KavenegarService(
			    config('services.kavenegar.uri'),
                config('services.kavenegar.token')
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
