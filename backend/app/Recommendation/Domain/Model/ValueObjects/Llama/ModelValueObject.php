<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Llama;

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
        $this->model = config('llama.model');
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
