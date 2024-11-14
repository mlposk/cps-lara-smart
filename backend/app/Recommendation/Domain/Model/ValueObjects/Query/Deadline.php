<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Query;

use Exception;

class Deadline
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $deadline) {}

    public function __toString(): string
    {
        return $this->deadline ?? '';
    }
}
