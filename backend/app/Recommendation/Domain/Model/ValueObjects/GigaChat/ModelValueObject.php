<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GigaChat;

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
        $this->model = 'GigaChat-Pro';
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
