<?php
namespace App\Recommendation\Presentation\API;

use App\Recommendation\Application\UseCases\Commands\StoreRecommendationEntryCommand;
use App\Recommendation\Application\UseCases\Queries\FindAllRecommendationsQuery;
use Illuminate\Http\Request;
use App\Common\Infrastructure\Laravel\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class RecommendationIndexController extends Controller
{
    public function getAll()
    {
        try {
            return response()->success((new FindAllRecommendationsQuery())->handle());
        } catch (\Throwable $exception) {
            return response()->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        try {
            $payload = \App\Recommendation\Application\Mappers\RecommendationMapper::fromRequest($request);
            $recommendation = (new StoreRecommendationEntryCommand($payload))->execute();
            return response()->success($recommendation, Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            return response()->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
