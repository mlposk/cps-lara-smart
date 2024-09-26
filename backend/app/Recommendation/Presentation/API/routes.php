<?php

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
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



        $taskId = str()->uuid();
        $emailTo = 'chedia@mail.ru';
        $file = 'url';


        $attachmentDto = new AttachmentRecommendationDto(
            taskId: str()->uuid(),
            userEmail: 'chedia@mail.ru',
            filePath: ''
        );


//        $rr = ' ';
//
//       $url = Storage::disk('recommendations')->url('61f1e378-e861-4792-ba20-358f120e7b25.png');
//
//
//       $res = Mail::to('chedia@mail.ru')
//            ->send(new \App\Recommendation\Infrastructure\Mail\ProcessedFileEmail($url));

//        $rr = ' ';
//
//        if($request->has('file')){
//            $file = $request->file('file');
//            $fileResults = Storage::disk('recommendations')->putFileAs('/',
//                $file,
//                str()->uuid() . '.' . $file->extension()
//            );
//
//            $rr= '';
//        }

    });
});
