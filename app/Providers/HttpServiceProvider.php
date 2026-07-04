<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class HttpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Http::macro('shwary', function () {
            return Http::withOptions([
                'verify' => !app()->environment('local'),
                'timeout' => 60,
            ])->withHeaders([
                'X-API-Key' => config('services.shwary.merchant_ikey'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
        });
    }
}