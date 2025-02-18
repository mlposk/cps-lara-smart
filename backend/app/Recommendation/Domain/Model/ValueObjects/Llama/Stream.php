<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Llama;

class Stream
{
    public function __construct(
        private readonly bool $isStream = false
    )
    {
    }

    public function getStream(): bool
    {
        return $this->isStream;
    }
}
