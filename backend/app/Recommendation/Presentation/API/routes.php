<?php

use App\Recommendation\Presentation\API\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recommendation',
], function () {
    Route::get('index', [RecommendationController::class, 'getAll'])->name('recommendation.index');
    Route::post('file', [RecommendationController::class, 'handleFile'])->name('recommendation.file');
    Route::post('text', [RecommendationController::class, 'handleText'])->name('recommendation.text');
});
