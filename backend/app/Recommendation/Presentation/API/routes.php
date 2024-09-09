<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recommendation'
], function () {
    Route::get("getAll", [\App\Recommendation\Presentation\API\RecommendationIndexController::class, "getAll"]);
    Route::post("", [\App\Recommendation\Presentation\API\RecommendationIndexController::class, "store"]);
});
