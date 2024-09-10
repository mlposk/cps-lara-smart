<?php

namespace App\Recommendation\Application\UseCases\Queries;

use App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface;

class FindAllRecommendationsQuery
{
    private RecommendationRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(RecommendationRepositoryInterface::class);
    }

    public function handle(): array
    {
        return $this->repository->getAll();
    }
}
