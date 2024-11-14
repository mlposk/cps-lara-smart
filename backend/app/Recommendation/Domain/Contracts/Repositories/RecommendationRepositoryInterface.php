<?php

namespace App\Recommendation\Domain\Contracts\Repositories;

use App\Recommendation\Domain\Model\Aggregates\Recommendation;

interface RecommendationRepositoryInterface
{
    public function getAll(): array;

    public function store(Recommendation $recommendation);
}
