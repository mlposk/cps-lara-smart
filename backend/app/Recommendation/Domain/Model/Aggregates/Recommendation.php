<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use App\Recommendation\Domain\Model\Entities\Task;

class Recommendation
{

    private Сonclusion $conclusion;
    private RecommendationExpertInterface $expert;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __construct(
        public ?int $id,
        public string $query,
        public ?string $answer = null,
    ) {
        $this->initDependencies();
    }


    private function initСonclusion(){
        $this->conclusion = $this->expert->getConclusion();
    }

    private function initAnswer(){
        $this->suggestion = $this->provider->getSuggestion($this->conclusion);
    }

    public function suggest(){
        $this->process();
    }


    private function initDependencies()
    {
        $this->expert = app()->make(RecommendationExpertInterface::class);
        $this->provider = app()->make(RecommendationProviderInterface::class);
    }

    private function process()
    {
        // Save suggestion
        // Return $this
    }
}
