<?php

namespace App\Recommendation\Application\Providers;

use Illuminate\Support\ServiceProvider;

class RecommendationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \App\Recommendation\Domain\Repositories\RecommendationRepositoryInterface::class,
            \App\Recommendation\Application\Repositories\Eloquent\RecommendationRepository::class,
        );
    }
}
