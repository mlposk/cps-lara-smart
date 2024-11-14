<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

use Exception;

class SmartTitle
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $smartTitle)
    {
        if (! $smartTitle) {
            throw new Exception('required');
        }
    }

    public function __toString(): string
    {
        return $this->smartTitle;
    }
}
