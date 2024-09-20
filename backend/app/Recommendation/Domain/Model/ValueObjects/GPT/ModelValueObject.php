<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GPT;

class ModelValueObject
{
    private string $model;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initModel();
    }

    private function initModel(): void
    {
        $this->model = 'gpt-4o-mini';
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
