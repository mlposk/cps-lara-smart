<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Domain\Model\Entities\Task;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;
use Illuminate\Http\Request;

class RecommendationMapper{


    public static function fromRequest(Request $request, ?int $id = null): Recommendation
    {
        // Тут описывает проверку на обязательное поле

        return new Recommendation(
            id: $id,
            query: $request->input('query'),
            answer: $request->input('answer')
        );
    }

    public static function fromEloquent(RecommendationEloquentModel $recommendationEloquentModel): Recommendation
    {
        return new Recommendation(
            id: $recommendationEloquentModel->id,
            query: $recommendationEloquentModel->query,
            answer: $recommendationEloquentModel->answer
        );
    }

    public static function toEloquent(Recommendation $recommendation): RecommendationEloquentModel
    {
        $recommendationEloquentModel = new RecommendationEloquentModel();
        $recommendationEloquentModel->query = $recommendation->query;
        $recommendationEloquentModel->answer = $recommendation->answer;
        return $recommendationEloquentModel;
    }
}
