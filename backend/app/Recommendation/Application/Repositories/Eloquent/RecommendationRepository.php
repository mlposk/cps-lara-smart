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

        foreach ($recommendation->getAnswersAssocArray() as $answer) {
            $answerEloquentModel = AnswerMapper::toEloquent($answer);
            $answerEloquentModel->recommendation_id = $recommendationId;
            $answerEloquentModel->save();
        }

        // Публикация событий
        foreach ($recommendation->getEvents() as $event) {
            event($event);
        }

        // Очищаем события после публикации
        $recommendation->clearEvents();

        return RecommendationMapper::fromEloquent($recommendationEloquent);

    }
}
