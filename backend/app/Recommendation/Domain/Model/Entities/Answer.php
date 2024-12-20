<?php

namespace App\Recommendation\Domain\Model\Entities;

use App\Common\Domain\Entity;
use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderResponse;
use App\Recommendation\Domain\Model\ValueObjects\Provider\Recommendation;
use App\Recommendation\Domain\Model\ValueObjects\Provider\SmartTitle;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Query\QueryCollection;
use App\Recommendation\Domain\Model\ValueObjects\QueryResponse\QueryResponse;
use App\Recommendation\Domain\Model\ValueObjects\QueryResponse\QueryResponseCollection;
use Exception;
use Faker\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;

class Answer extends Entity
{
    private QueryCollection $queries;

    private RecommendationExpertInterface $expert;

    private RecommendationProviderInterface $provider;

    private QueryResponseCollection $queryResponses;

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
        $this->queryResponseCollection();
        $this->initQueryCollection();
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
        $this->provider = app()->make(RecommendationProviderInterface::class);
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        /** @var Query $query */
        foreach ($this->queries->collection as $query) {
            if (config('app.debug')) {
                $queryResponse = new QueryResponse($query, $this->stubAnswer());
            } else {
                $message = $this->expert->getMessage(
                    $query->toArray(),
                    true
                );
                $response = $this->provider->getSuggestion($message);
                $queryResponse = new QueryResponse($query, $response);
            }

            $this->addQueryResponse(
                $queryResponse
            );
        }
    }

    public function addQuery(Query $query): void
    {
        $this->queries->push($query);
    }

    public function addQueryResponse(QueryResponse $queryResponse): void
    {
        $this->queryResponses->push(
            $queryResponse
        );
    }

    public function toArray(): array
    {
        return $this->queryResponses->toArray();
    }

    public function toAssocArray(): array
    {
        return $this->queryResponses->toAssocArray();
    }

    /**
     * @throws Exception
     */
    private function stubAnswer(): ProviderResponse
    {
        $faker = Factory::create();

        return new ProviderResponse(
            new SmartTitle($faker->realText(30)),
            new Recommendation($faker->realText)
        );
    }

    private function queryResponseCollection(): void
    {
        $this->queryResponses = new QueryResponseCollection();
    }

    private function initQueryCollection(): void
    {
        $this->queries = new QueryCollection();
    }
}
