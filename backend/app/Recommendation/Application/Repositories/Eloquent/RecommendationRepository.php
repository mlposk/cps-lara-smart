<?php

namespace App\Recommendation\Application\Repositories\Eloquent;

use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Domain\Repositories\RecommendationRepositoryInterface;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;
use Illuminate\Http\Request;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    public function getAll(): array
    {
        return RecommendationEloquentModel::all()->toArray();
        // TODO: Implement index() method.
    }

    public function store(Recommendation $recommendation)
    {
        $recommendationEloquent = RecommendationMapper::toEloquent($recommendation);
        $recommendationEloquent->save();

        return RecommendationMapper::fromEloquent($recommendationEloquent);
    }
}
