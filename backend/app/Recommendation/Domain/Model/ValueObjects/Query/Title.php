<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Query;

use Exception;

class Title
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $title)
    {
        if (! $title) {
            throw new Exception('required');
        }
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }
}
