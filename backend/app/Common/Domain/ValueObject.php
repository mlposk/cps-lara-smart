<?php

declare(strict_types=1);

namespace App\Common\Domain;

use JsonSerializable;

abstract class ValueObject implements JsonSerializable
{
    abstract public function toArray(): array;

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
