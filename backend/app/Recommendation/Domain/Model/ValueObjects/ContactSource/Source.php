<?php

namespace App\Recommendation\Domain\Model\ValueObjects\ContactSource;

class Source
{
    public const HTTP = 'http';

    public const EMAIL = 'email';

    public function __construct(public ?string $source)
    {
    }

    public function __toString(): string
    {
        return $this->source ?? '';
    }
}
