<?php

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Infrastructure\Jobs\PerformRecommendationFile;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use App\Recommendation\Presentation\API\RecommendationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::group([
    "prefix" => "recommendation"
], function () {
    Route::get("index", [RecommendationController::class, "getAll"])->name("recommendation.index");
    Route::post("file", [RecommendationController::class, "handleFile"])->name("recommendation.file");
    Route::post("text", [RecommendationController::class, "handleText"])->name("recommendation.text");

    Route::post('/email', function (Request $request) {
        if(!$request->has('file')){
            return;
        }
        $uuid = str()->uuid();

        $file = $request->file('file');
        $fileResults = Storage::disk('public')->putFileAs('recommendations',
            $file,
            $uuid . '.' . $file->extension()
        );

        $attachmentDto = new AttachmentRecommendationDto(
            jobId: $uuid,
            userEmail: 'chedia@mail.ru',
            filePath: Storage::disk('public')->url($fileResults)
        );

        $job = new PerformRecommendationFile($attachmentDto);
        dispatch($job);
    });
});
