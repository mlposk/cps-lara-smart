<?php

namespace App\Recommendation\Domain\Model\ValueObjects;

use Exception;

class Body
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $body)
    {
    }

    public function __toString(): string
    {
        return $this->body;
    }
}
