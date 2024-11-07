<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\Entities\Answer;
use App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderResponse;
use App\Recommendation\Domain\Model\ValueObjects\Provider\Recommendation;
use App\Recommendation\Domain\Model\ValueObjects\Provider\SmartTitle;
use App\Recommendation\Domain\Model\ValueObjects\Query\Body;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Query\QueryCollection;
use App\Recommendation\Domain\Model\ValueObjects\Query\Title;
use App\Recommendation\Domain\Model\ValueObjects\Query\Project;
use App\Recommendation\Domain\Model\ValueObjects\Query\Deadline;

use App\Recommendation\Domain\Model\ValueObjects\QueryResponse\QueryResponse;
use App\Recommendation\Infrastructure\EloquentModels\AnswerEloquentModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;


class AnswerMapper
{


    /**
     * @throws Exception
     */
    public static function fromEloquentCollection(Collection $collection): Answer
    {
        $model = new Answer();

        foreach ($collection as $queryResponse) {

            $query = json_decode($queryResponse->query, true);
            $response = json_decode($queryResponse->answer, true);

            $queryObject = QueryMapper::fromArray($query);
            $responseObject = new ProviderResponse(
                new SmartTitle($response['smartTitle']),
                new Recommendation($response['recommendation'])
            );

            $model->addQuery($queryObject);
            $model->addQueryResponse(new QueryResponse($queryObject, $responseObject));
        }

        return $model;
    }


    /**
     * @throws Exception
     */
    public static function fromRequest(Request $request): Answer
    {
        $collection = collect();
        $collection->push(
            new Query(
                title: new Title($request->input('title')),
                body: new Body($request->input('body')),
                project: new Project($request->input('project', '')),
                deadline: new Deadline($request->input('deadline', ''))
            )
        );

        return new Answer(new QueryCollection($collection));
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $array): Answer
    {
        $domainModel = new Answer();

        foreach ($array as $item) {
            $queryObject = QueryMapper::fromArray($item);
            $domainModel->addQuery($queryObject);
        }
        return $domainModel;
    }


    public static function toEloquent($answer): AnswerEloquentModel
    {
        $companyEloquent = new AnswerEloquentModel();
        $companyEloquent->query = json_encode($answer['query']);
        $companyEloquent->answer = json_encode($answer['response']);
        return $companyEloquent;
    }

}
