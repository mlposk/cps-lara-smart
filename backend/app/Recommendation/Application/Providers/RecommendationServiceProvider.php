<?php

namespace App\Recommendation\Application\Providers;

use Illuminate\Support\ServiceProvider;

class RecommendationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface::class,
            \App\Recommendation\Application\Repositories\Eloquent\RecommendationRepository::class,
        );

        $this->app->bind(
            \App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface::class,
//            \App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGPT::class,
            \App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGigaChat::class,
        );

        $this->app->bind(
            \App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface::class,
//            \App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGPT::class,
            \App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGigaChat::class,
        );
    }
}
