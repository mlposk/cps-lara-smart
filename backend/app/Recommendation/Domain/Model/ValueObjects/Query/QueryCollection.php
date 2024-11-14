<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Query;

use App\Common\Domain\ValueObject;
use Exception;
use Illuminate\Support\Collection;

class QueryCollection extends ValueObject
{
    public Collection $collection;

    /**
     * @throws Exception
     */
    public function __construct(

    ) {
        $this->collection = collect();
    }

    public function push(Query $query): void
    {
        $this->collection->push($query);
    }

    public function toArray(): array
    {
        return $this->collection->map(fn ($item) => $item->toArray())->all();
    }
}
