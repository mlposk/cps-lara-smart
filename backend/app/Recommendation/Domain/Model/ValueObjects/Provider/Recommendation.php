<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

class Recommendation
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $recommendation) {}

    public function __toString(): string
    {
        return $this->recommendation;
    }
}
