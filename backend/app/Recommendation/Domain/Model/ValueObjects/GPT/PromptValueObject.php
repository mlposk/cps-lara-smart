<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GPT;

class PromptValueObject
{
    private string $task;

    private string $data;

    private string $postCondition;

    private array $taskData;

    public function __construct(array $taskData, ?bool $isPostCondition = null)
    {
        $this->postCondition = $isPostCondition ? 'true' : 'false';
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
        if ($this->postCondition === 'true') {
            $this->postCondition = 'Верни следующее: {Только название задачи по SMART без вводного слова} {символ %d%} {Почему она не попадает под SMART, распиши критерии, но не пиши про Time Bound}';
        }
    }

    public function getPrompt(): array
    {
        return [
            [
                'role' => 'user',
                'content' => "$this->task $this->data $this->postCondition",
            ],
        ];
    }
}
