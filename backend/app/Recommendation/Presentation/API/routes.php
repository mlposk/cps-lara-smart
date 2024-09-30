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
});
