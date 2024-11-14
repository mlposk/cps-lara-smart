<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Domain\Model\Entities\Answer;
use App\Recommendation\Domain\Model\ValueObjects\ContactSource\Source;
use App\Recommendation\Domain\Model\ValueObjects\ContactSource\Value;
use App\Recommendation\Domain\Model\ValueObjects\Query\Body;
use App\Recommendation\Domain\Model\ValueObjects\Query\Deadline;
use App\Recommendation\Domain\Model\ValueObjects\Query\Project;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Query\Title;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecommendationMapper
{
    /**
     * @throws \Exception
     */
    public static function fromEloquent(RecommendationEloquentModel $recommendationEloquentModel): Recommendation
    {
        $recommendation = new Recommendation(
            id: $recommendationEloquentModel->id,
            uuid: $recommendationEloquentModel->uuid,
            source: $recommendationEloquentModel->source,
            sourceValue: $recommendationEloquentModel->source_value,
        );

        $answer = AnswerMapper::fromEloquentCollection($recommendationEloquentModel->answers);

        $recommendation->addAnswer($answer);

        return $recommendation;
    }

    public static function toEloquent(Recommendation $recommendation): RecommendationEloquentModel
    {
        $companyEloquent = new RecommendationEloquentModel;
        if ($recommendation->id) {
            $companyEloquent = RecommendationEloquentModel::query()->findOrFail($recommendation->id);
        }

        $companyEloquent->uuid = $recommendation->uuid;
        $companyEloquent->source = $recommendation->source;
        $companyEloquent->source_value = $recommendation->sourceValue;

        return $companyEloquent;
    }

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function fromRequest(Request $request): Recommendation
    {
        $answer = new Answer;

        $query = new Query(
            title: new Title($request->input('title')),
            body: new Body($request->input('body')),
            project: new Project($request->input('project', '')),
            deadline: new Deadline($request->input('deadline', ''))
        );
        $answer->addQuery($query);

        $recommendation = new Recommendation(
            id: null,
            uuid: Str::uuid(),
            source: Source::HTTP,
            sourceValue: new Value($request->ip()),
        );

        $recommendation->addAnswer($answer);

        return $recommendation;
    }

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public static function fromArrayWithAnswer(array $array, array $contactSource): Recommendation
    {
        $recommendation = static::fromArray($array, $contactSource);
        $recommendation->addAnswer(AnswerMapper::fromArray($array));

        return $recommendation;
    }

    public static function fromArray(array $contactSource): Recommendation
    {
        return new Recommendation(
            id: null,
            uuid: Str::uuid(),
            source: $contactSource['source'],
            sourceValue: $contactSource['sourceValue'],
        );
    }
}
