<?php

namespace App\Recommendation\Presentation\API;

use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Application\UseCases\Commands\StoreRecommendationEntryCommand;
use App\Recommendation\Application\UseCases\Queries\FindAllRecommendationsQuery;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use App\Common\Infrastructure\Laravel\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;

class RecommendationController extends Controller
{
    public function getAll()
    {
        try {
            return response()->success((new FindAllRecommendationsQuery())->handle());
        } catch (\Throwable $exception) {
            return response()->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    public function handleFile(Request $request)
    {

        $validated = $request->validated();


        $result = [];
        $arrayFromFile = $request;
        foreach ($arrayFromFile as $payload) {
            $result[] = (new StoreRecommendationEntryCommand($payload))->execute();
        }

        $newFile = $result;
        return "file endpoint";
    }

    /**
     * @throws BindingResolutionException
     */
    public function handleText(Request $request)
    {
        try {
            $recommendation = RecommendationMapper::fromRequest($request);
            $recommendation->execute();
            return response()->success($recommendation->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $throwable) {
            return response()->error($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
