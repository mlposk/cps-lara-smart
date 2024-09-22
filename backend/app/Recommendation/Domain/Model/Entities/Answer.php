<?php

namespace App\Recommendation\Domain\Model\Entities;

use App\Common\Domain\Entity;
use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use App\Recommendation\Domain\Model\ValueObjects\Provider\Result;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGPT;
use App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGPT;
use Illuminate\Contracts\Container\BindingResolutionException;

class Answer extends Entity
{

    private Query $query;
    private RecommendationExpertInterface $expert;
    private RecommendationProviderInterface $provider;
    private Result $answer;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @throws BindingResolutionException
     */
    private function init(): void
    {
        $this->initExpert();
        $this->initProvider();
    }

    /**
     * @throws BindingResolutionException
     */
    private function initExpert(): void
    {
        $this->expert = app()->make(RecommendationExpertInterface::class);
    }

    /**
     * @throws BindingResolutionException
     */
    private function initProvider(): void
    {
        $this->provider = app()->make(RecommendationProviderInterface::class);;
    }

    public function execute(){
        $message = $this->expert->getMessage($this->query->toArray(), true);
        $this->answer = $this->provider->getSuggestion($message);
    }

    /**
     * @param Query $query
     * @return void
     */
    public function setQuery(Query $query): void
    {
        $this->query = $query;
    }

    public function toArray(): array
    {
        return $this->answer->toArray();
    }
}
