<?php

namespace App\Recommendation\Domain\Model\Entities;

use App\Common\Domain\Entity;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Expert\ExpertGPT;
use App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderGPT;

class Answer extends Entity
{

    private Query $query;
    private ExpertGPT $expert;
    private ProviderGPT $provider;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initExpert();
        $this->initProvider();
    }

    private function initExpert(): void
    {
        $this->expert = new ExpertGPT();
    }

    private function initProvider(): void
    {
        $this->provider = new ProviderGPT();
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
        $message = $this->expert->getMessage($this->query->toArray(), true);
        return $this->provider->getSuggestion($message);
    }
}
