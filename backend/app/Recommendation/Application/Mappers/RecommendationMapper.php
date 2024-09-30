<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Domain\Model\Entities\Answer;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class RecommendationMapper
{


    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function fromRequest(Request $request, ?int $id = null): Recommendation
    {
        $recommendation = new Recommendation(
            id: $id,
            query: QueryMapper::fromRequest($request),
            answer: new Answer()
        );

        return $recommendation->setQueryToAnswer();
    }

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function fromArray(array $array, ?int $id = null): Recommendation
    {
        $recommendation = new Recommendation(
            id: $id,
            query: QueryMapper::fromArray($array),
            answer: new Answer()
        );

        return $recommendation->setQueryToAnswer();
    }


}
