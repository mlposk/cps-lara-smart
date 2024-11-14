<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GPT;

class StreamValueObject
{
    private bool $stream;

    public function __construct(bool $isStream = false)
    {
        $this->stream = $isStream;
    }

    public function getStream(): bool
    {
        return $this->stream;
    }
}
