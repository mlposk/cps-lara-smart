<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

use App\Common\Domain\ValueObject;
use Exception;

class ProviderResponse extends ValueObject
{
    /**
     * @throws Exception
     */
    public function __construct(
        public SmartTitle $smartTitle,
        public Recommendation $recommendation,
    ) {}

    public function toArray(): array
    {
        return [
            'smartTitle' => (string) $this->smartTitle,
            'recommendation' => (string) $this->recommendation,
        ];
    }
}
