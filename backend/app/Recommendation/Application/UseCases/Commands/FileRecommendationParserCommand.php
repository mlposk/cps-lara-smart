<?php

namespace App\Recommendation\Application\UseCases\Commands;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use Illuminate\Support\Facades\Mail;

class FileRecommendationParserCommand
{

    public function __construct(private readonly AttachmentRecommendationDto $attachmentRecommendationDto)
    {
    }

    public function execute()
    {
        $this->sendMail();
        // fillPayload // Из файла собрать данные
        // fillRecommendation // Получаешь рекомендации
        // fillFile
        // SendMail

        //
    }

    private function sendMail()
    {
        Mail::to($this->attachmentRecommendationDto->userEmail)
            ->send(new ProcessedFileEmail($this->attachmentRecommendationDto));
    }
}
