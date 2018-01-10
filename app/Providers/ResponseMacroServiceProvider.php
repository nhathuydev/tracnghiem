<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data) {
            return response()->json([
                'error' => false,
                'data' => $data,
            ], 200);
        });
        Response::macro('error', function ($data, $error_code = 400) {
            return response()->json([
                'error' => true,
                'data' => $data,
            ], $error_code);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
