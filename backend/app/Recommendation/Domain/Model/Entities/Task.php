<?php

namespace App\Recommendation\Domain\Model\Entities;

class Task{
    public string $model;
    public string $response;
    public function __construct(
        public ?int $id,
        public readonly string $query,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'model' => $this->model,
            'query' => $this->query,
            'response' => $this->response,
        ];
    }
}
