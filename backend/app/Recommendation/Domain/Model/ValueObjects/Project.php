<?php

namespace App\Recommendation\Domain\Model\ValueObjects;

use Exception;

class Project
{
    /**
     * @throws Exception
     */
    public function __construct(public ?string $project)
    {
    }
    public function __toString(): string
    {
        return $this->project;
    }

}