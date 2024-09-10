<?php
namespace App\Recommendation\Presentation\API;

use App\Recommendation\Application\UseCases\Commands\StoreRecommendationEntryCommand;
use App\Recommendation\Application\UseCases\Queries\FindAllRecommendationsQuery;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use App\Common\Infrastructure\Laravel\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        $result = [];
        $arrayFromFile = $request;
        foreach ($arrayFromFile as $payload){
            $result[] = (new StoreRecommendationEntryCommand($payload))->execute();
        }

        $newFile = $result;
        return "file endpoint";
    }

    public function handleText(Request $request)
    {
        return "text endpoint";
    }
}
