<?php

namespace App\Recommendation\Application\DTO;

use Illuminate\Http\UploadedFile;

class AttachmentRecommendationDto{
    public function __construct(
        public readonly string $userEmail,
        public readonly array|UploadedFile|null $file,
        public readonly string $jobId
    )
    {
    }
}
