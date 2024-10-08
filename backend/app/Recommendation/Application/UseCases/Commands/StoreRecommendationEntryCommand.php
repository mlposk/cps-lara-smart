<?php

namespace App\Recommendation\Application\UseCases\Commands;

use App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use Illuminate\Contracts\Container\BindingResolutionException;

class StoreRecommendationEntryCommand
{
    private RecommendationRepositoryInterface $repository;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        private readonly Recommendation $recommendation
    )
    {
        $this->repository = app()->make(RecommendationRepositoryInterface::class);
    }

    public function execute()
    {
        return $this->repository->store($this->recommendation);
    }
}
