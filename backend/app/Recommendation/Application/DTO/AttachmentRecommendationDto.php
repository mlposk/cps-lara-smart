<?php

namespace App\Recommendation\Application\DTO;

class AttachmentRecommendationDto{
    public function __construct(
        public readonly string $jobId,
        public readonly string $userEmail,
        public readonly string $filePath,
    )
    {
    }
}
