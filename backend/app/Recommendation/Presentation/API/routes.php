<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recommendation'
], function () {

    Route::get("index", [\App\Recommendation\Presentation\API\RecommendationController::class, "getAll"])->name('recommendation.index');
    Route::post("file", [\App\Recommendation\Presentation\API\RecommendationController::class, "handleFile"])->name('recommendation.file');
    Route::post("text", [\App\Recommendation\Presentation\API\RecommendationController::class, "handleText"])->name('recommendation.text');

});
