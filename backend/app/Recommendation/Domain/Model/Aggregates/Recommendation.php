<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Recommendation\Domain\Model\Entities\Task;

class Recommendation
{

    private Task $task;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __construct(
        public ?int $id,
        public string $query,
        public ?string $answer = null,
    ) {
        $this->task = new Task($this->id, $this->query);
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'task' => $this->task
        ];
    }
}
