<?php

namespace App\Recommendation\Domain\Model\ValueObjects\QueryResponse;

use App\Recommendation\Domain\Model\ValueObjects\Provider\ProviderResponse;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;

class QueryResponse{
    public function __construct(
        public readonly Query $query,
        public readonly ProviderResponse $result
    )
    {
    }
}
