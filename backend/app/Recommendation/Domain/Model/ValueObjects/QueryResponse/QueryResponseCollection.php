<?php

namespace App\Recommendation\Domain\Model\ValueObjects\QueryResponse;

use App\Recommendation\Domain\Model\ValueObjects\Provider\Result;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use Illuminate\Support\Collection;

class QueryResponseCollection
{
    private Collection $collection;

    public function __construct(
    ) {
        $this->collection = collect();
    }

    public function push(QueryResponse $queryResponse): void
    {
        $this->collection->push($queryResponse);
    }
    public function separateData(): array
    {
         return $this->collection->map(function ($item) {
            return [
                'query' => $item->query->toArray(),
                'response' => $item->result->toArray()
            ];
        })->all();
    }
    public function toArray(): array
    {
        return $this->collection->map(function ($item) {
            return [
               ...$item->query->toArray(),
               ...$item->result->toArray()
            ];
        })->all();
    }
}
