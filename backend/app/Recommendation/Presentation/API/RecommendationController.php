<?php

namespace App\Recommendation\Presentation\API;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Application\UseCases\Commands\FileRecommendationParserCommand;
use App\Recommendation\Application\UseCases\Commands\StoreRecommendationEntryCommand;
use App\Recommendation\Application\UseCases\Queries\FindAllRecommendationsQuery;
use App\Recommendation\Infrastructure\EloquentModels\RecommendationEloquentModel;
use App\Recommendation\Infrastructure\Jobs\PerformRecommendationFile;
use App\Recommendation\Infrastructure\Mail\ConfirmEmail;
use App\Common\Infrastructure\Laravel\Controllers\Controller;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
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
     * @throws \Exception
     */
    public function handleFile(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required',
                'email' => 'required'
            ]);


            $recommendation = RecommendationMapper::fromArray([
                'source' => 'email',
                'sourceValue' => $request->input('email')
            ]);


            $attachmentDto = new AttachmentRecommendationDto(
                userEmail: $request->input('email'),
                filePath: $request->file('file')->getPathName(),
                jobId: $recommendation->uuid
            );


            $job = new PerformRecommendationFile($attachmentDto, $recommendation);
            dispatch($job);

            Mail::to($attachmentDto->userEmail)
                ->send(new ConfirmEmail($attachmentDto));

        } catch (ValidationException $throwable) {
            return response()->error($throwable->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $throwable) {
            return response()->error($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    public function handleText(Request $request)
    {
        try {

            $recommendation = RecommendationMapper::fromRequest($request);
            $recommendation = (new StoreRecommendationEntryCommand($recommendation))->execute();
            return response()->success($recommendation->toArray());

        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $throwable) {
            return response()->error($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
