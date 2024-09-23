<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Common\Domain\AggregateRoot;
use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use App\Recommendation\Domain\Model\Entities\Answer;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use Illuminate\Contracts\Container\BindingResolutionException;

class Recommendation extends AggregateRoot
{

    public function __construct(
        public ?int $id,
        public Query $query,
        public Answer $answer
    ) {
    }

    public function setQueryToAnswer(): self
    {
        $this->answer->setQuery($this->query);
        return $this;
    }

    public function execute(): void
    {
        $this->answer->execute();
    }

    public function toArray(): array
    {
        return [
            'query' => $this->query->toArray(),
            'answer' => $this->answer->toArray()
        ];
    }
}
