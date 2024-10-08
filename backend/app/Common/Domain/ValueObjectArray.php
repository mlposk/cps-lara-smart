<?php

namespace App\Common\Domain;

abstract class ValueObjectArray extends \ArrayIterator implements \JsonSerializable
{
    abstract public function jsonSerialize(): array;
}
