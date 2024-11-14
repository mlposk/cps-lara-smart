<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Common\Domain\AggregateRoot;
use App\Recommendation\Domain\Events\RecommendationComplete;
use App\Recommendation\Domain\Model\Entities\Answer;
use Exception;

class Recommendation extends AggregateRoot
{
    private array $events = [];

    private Answer $answer;

    public function __construct(
        public ?int $id,
        public string $uuid,
        public string $source,
        public string $sourceValue,
    ) {}

    public function addAnswer(Answer $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @throws Exception
     */
    public function executeAnswer(): void
    {
        $this->answer->execute();
    }

    public function getAnswers(): array
    {
        return $this->answer->toArray();
    }

    public function getAnswersAssocArray(): array
    {
        return $this->answer->toAssocArray();
    }

    public function toArray(): array
    {
        return [
            'answer' => $this->answer->toArray(),
            'source' => $this->source,
            'uuid' => $this->uuid,
            'sourceValue' => $this->sourceValue,
            'id' => $this->id,
        ];
    }

    private function throwCompleteEvent(): RecommendationComplete
    {
        ['id' => $id, 'query' => $query, 'answer' => $answer, 'source' => $source, 'sourceValue' => $sourceValue] = $this->toArray();

        return new RecommendationComplete($id, $query, $answer, $source, $sourceValue);
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
