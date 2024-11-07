<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Common\Domain\AggregateRoot;
use App\Recommendation\Domain\Events\RecommendationCompete;
use App\Recommendation\Domain\Model\Entities\Answer;
use App\Recommendation\Domain\Model\Entities\ContactSource;
use App\Recommendation\Domain\Model\ValueObjects\Query\Body;
use App\Recommendation\Domain\Model\ValueObjects\Query\Deadline;
use App\Recommendation\Domain\Model\ValueObjects\Query\Project;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Query\QueryCollection;

use App\Recommendation\Domain\Model\ValueObjects\Query\Title;
use Exception;
use Illuminate\Http\Request;


class Recommendation extends AggregateRoot
{

    private array $events = [];
    private Answer $answer;
    public function __construct(
        public ?int $id,
        public string $source,
        public string $sourceValue,
    ) {
    }

    public function addAnswer(Answer $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @throws Exception
     */
    public function getRecommendation(): void
    {
        $this->answer->execute();
    }

    public function getAnswers(): array
    {
       return  $this->answer->toArray();
    }
    public function getAnswerSeparateDate(): array
    {
        return  $this->answer->separateData();
    }

    public function toArray(): array
    {
        return [
            'answer' => $this->answer->toArray(),
            'source' => $this->source,
            'sourceValue' => $this->sourceValue,
            'id' => $this->id
        ];
    }

    private function throwCompleteEvent(): RecommendationCompete
    {
        ['id' => $id, 'query' => $query, 'answer' => $answer, 'source' => $source, 'sourceValue' => $sourceValue] = $this->toArray();
       return new RecommendationCompete($id, $query, $answer, $source, $sourceValue);
    }

    public function clearEvents(): void {
        $this->events = [];
    }

    public function getEvents(): array {
        return $this->events;
    }
}
