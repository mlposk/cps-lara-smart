<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Query;

class Body
{
    public function __construct(public ?string $body)
    {
    }

    public function __toString(): string
    {
        return $this->body ?? '';
    }
}
