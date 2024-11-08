<?php

namespace App\Recommendation\Domain\Model\ValueObjects\ContactSource;

class Value{

    public function __construct(public ?string $value)
    {
    }
    public function __toString(): string
    {
        return $this->value ?? "";
    }
}
