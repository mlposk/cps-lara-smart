<?php

namespace App\Recommendation\Application\Repositories\Eloquent;

use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    public function getAll(): array
    {
        return RecommendationEloquentModel::all()->toArray();
    }

    public function store(Recommendation $recommendation)
    {
        $recommendationEloquent = RecommendationMapper::toEloquent($recommendation);
        $recommendationEloquent->save();

        return RecommendationMapper::fromEloquent($recommendationEloquent);
    }
}
