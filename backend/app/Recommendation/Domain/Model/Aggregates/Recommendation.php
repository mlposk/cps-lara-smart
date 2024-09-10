<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class Recommendation
{
    private Сonclusion $conclusion;
    private Suggestion $suggestion;

    private RecommendationExpertInterface $expert;
    private RecommendationProviderInterface $provider;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        public ?int $id,
        public string $query,
        public ?string $answer = null,
    ) {
        $this->initDependencies();
    }

    public function suggest()
    {
        $this->process();
    }

    /**
     * @throws BindingResolutionException
     */
    private function initDependencies(): void
    {
        $this->expert = app()->make(RecommendationExpertInterface::class);
        $this->provider = app()->make(RecommendationProviderInterface::class);
    }

    private function initConclusion(): void
    {
        $this->conclusion = $this->expert->getConclusion($this->query);
    }

    private function initAnswer(): void
    {
        $this->suggestion = $this->provider->getSuggestion($this->conclusion);
    }

    private function process()
    {
        // Save suggestion
        // Return $this
    }
}
