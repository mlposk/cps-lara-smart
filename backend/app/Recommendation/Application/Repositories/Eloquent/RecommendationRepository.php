<?php

namespace App\Recommendation\Application\Repositories\Eloquent;

use App\Recommendation\Application\Mappers\AnswerMapper;
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

    /**
     * @throws \Exception
     */
    public function store(Recommendation $recommendation)
    {

        $recommendationEloquent = RecommendationMapper::toEloquent($recommendation);
        $recommendationEloquent->save();
        $recommendationId = $recommendationEloquent->id;

        array_map(function ($answer) use ($recommendationId) {
            $AnswerEloquentModel = AnswerMapper::toEloquent($answer);
            $AnswerEloquentModel->recommendation_id = $recommendationId;
            $AnswerEloquentModel->save();
        }, $recommendation->getAnswers() );


        // Публикация событий
        foreach ($recommendation->getEvents() as $event) {
            event($event);
        }

        // Очищаем события после публикации
        $recommendation->clearEvents();



        return RecommendationMapper::fromEloquent($recommendationEloquent);

    }
}
