<?php

namespace App\Recommendation\Domain\Repositories;

use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use Illuminate\Http\Request;

interface RecommendationRepositoryInterface
{
    public function getAll(): array;
    public function store(Recommendation $recommendation);
}
