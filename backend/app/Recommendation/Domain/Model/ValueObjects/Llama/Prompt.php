<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Llama;

class Prompt
{
    private string $task;
    private string $data;
    private string $postCondition;

    public function __construct(
        private readonly array $taskData,
        private readonly ?bool $isPostCondition = false
    ) {
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
        $this->task = "Сформируй название задачи по методологии SMART. Если оно уже корректное, верни его без изменений, иначе исправь и объясни, что именно не соответствовало SMART.";
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
        if ($this->isPostCondition) {
            $this->postCondition = "Ответь используя JSON";
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
