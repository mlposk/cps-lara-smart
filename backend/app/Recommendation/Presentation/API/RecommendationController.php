<?php

namespace App\Recommendation\Presentation\API;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Application\UseCases\Commands\StoreRecommendationEntryCommand;
use App\Recommendation\Application\UseCases\Queries\FindAllRecommendationsQuery;
use App\Recommendation\Infrastructure\Jobs\PerformRecommendationFile;
use App\Recommendation\Infrastructure\Mail\ConfirmEmail;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use App\Common\Infrastructure\Laravel\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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
     * @throws \Exception
     */
    public function handleFile(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required',
                'email' => 'required'
            ]);

            $uuid = str()->uuid();
            $file = $request->file('file');

            $fileUrl = Storage::disk('public')->putFileAs(
                'recommendations',
                $file,
                $uuid . '.' . $file->getClientOriginalExtension()
            );

            $attachmentDto = new AttachmentRecommendationDto(
                jobId: $uuid,
                userEmail: $request->input('email'),
                filePath: Storage::disk('public')->path($fileUrl)
            );

            $job = new PerformRecommendationFile($attachmentDto);
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
            $recommendation->execute();
            return response()->success($recommendation->toArray());
        } catch (\DomainException $domainException) {
            return response()->error($domainException->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $throwable) {
            return response()->error($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
