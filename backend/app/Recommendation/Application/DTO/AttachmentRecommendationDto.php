<?php

namespace App\Recommendation\Application\DTO;

use Illuminate\Http\UploadedFile;

class AttachmentRecommendationDto{
    public function __construct(
        public readonly string $userEmail,
        public readonly string $filePath,
        public readonly string $jobId
    )
    {
    }
}
