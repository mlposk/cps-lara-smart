<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GigaChat;

class PromptValueObject
{
    private string $task;
    private string $data;
    private string $postCondition;
    private array $taskData;

    public function __construct(array $taskData, bool|null $isPostCondition = null)
    {
        $this->postCondition = $isPostCondition ? "true" : "false";
        $this->taskData = $taskData;
        $this->init();
    }

    private function init(): void
    {
        $this->initTask();
        $this->initData();
        $this->initPostCondition();
    }

    private function initTask(): void
    {
        $this->task = 'Сформируй название задачи по методологии SMART для следующей задачи:';
    }

    private function initData(): void
    {
        $this->data = str_replace(
            array_keys($this->taskData),
            array_values($this->taskData),
            '
                    Название задачи: "title".
                    Описание задачи: "body"
                    Проект: "project".
                '
        );
    }

    private function initPostCondition(): void
    {
        if ($this->postCondition === "true") {
            $this->postCondition = 'Ответ верни в следующем формате:
{Название задачи по SMART} ### {Краткое объяснение, почему название соответствует критериям Specific, Measurable, Achievable, Relevant (не упоминай Time Bound). Используй общий текст, не расписывай каждую категорию подробно.}';
        }
    }

    public function getPrompt(): array
    {
        return [
            [
                'role' => 'user',
                'content' => "$this->task $this->data $this->postCondition"
            ]
        ];
    }
}

