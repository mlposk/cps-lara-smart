<?php

namespace App\Recommendation\Application\DTO;

class TrackedEventDto{
    public function __construct(
        public readonly string $id,
        public readonly string $sourceType,
        public readonly string $targetAgent,
        public readonly string $query,
        public readonly string $answer,
        public readonly string $meta,
    )
    {
    }
}
