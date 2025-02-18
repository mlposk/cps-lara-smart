<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Llama;

class ResponseFormat
{
    private array $format;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initFormat();
    }

    private function initFormat(): void
    {
        $this->format = [
            "title" => "Схема для исправления заголовка по критериям SMART",
            "type" => "object",
            "properties" => [
                "smartTitle" => [
                    "type" => "string",
                    "description" => "Испрвленное название задачи по SMART без вводного слова."
                ],
                "recommendation" => [
                    "type" => "string",
                    "description" => "Почему изначальное название не попадает под критерии SMART, объясни почему по каждому из них, кроме Time Bound. Не пиши исправленное название."
                ]
            ],
            "required" => [
                "smartTitle",
                "recommendation"
            ]
        ];
    }

    public function getFormat(): array
    {
        return $this->format;
    }
}
