<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

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
        Response::macro('customJson', function (
            $is_error = true,
            $data = [],
            $other_data = [],
            $status_code = 200
        ) {
            $response_data = [
                'is_error' => $is_error,
                'data' => $data,
            ];

            if (!empty($other_data)) {
                $response_data = array_merge($response_data, $other_data);
            }

            return response()->json($response_data, $status_code);
        });

        Response::macro('errorJson', function (
            $data = [],
            $other_data = [],
            $status_code = 400
        ) {
            return response()->customJson(true, $data, $other_data, $status_code);
        });

        Response::macro('successJson', function (
            $data = [],
            $other_data = [],
            $status_code = 200
        ) {
            return response()->customJson(false, $data, $other_data, $status_code);
        });
    }
}
