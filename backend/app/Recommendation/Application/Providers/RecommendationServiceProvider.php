<?php

namespace App\Recommendation\Application\Providers;

use Illuminate\Support\ServiceProvider;

class RecommendationServiceProvider extends ServiceProvider
{
    public array $experts = [
        'gpt' => \App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGPT::class,
        'gigachat' => \App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGigaChat::class,
        'llama' => \App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertLlama::class,
    ];

    public array $providers = [
        'gpt' => \App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGPT::class,
        'gigachat' => \App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGigaChat::class,
        'llama' => \App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderLlama::class,
    ];

    public function register()
    {
        $model = config('expert.model');
        $this->app->bind(
            \App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface::class,
            \App\Recommendation\Application\Repositories\Eloquent\RecommendationRepository::class,
        );

        $this->app->bind(
            \App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface::class,
            $this->experts[$model],
        );

        $this->app->bind(
            \App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface::class,
            $this->providers[$model],
        );
    }
}
