<?php

namespace App\Common\Infrastructure\Laravel\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Response::macro('success', function ($data, $code = HttpResponse::HTTP_OK) {
            if ($data instanceof \JsonSerializable) {
                $data = $data->jsonSerialize();
            }
            return response()->json($data, $code);
        });

        Response::macro('error', function ($message, $code = HttpResponse::HTTP_BAD_REQUEST) {
            return response()->json(['error' => $message], $code);
        });
    }
}
