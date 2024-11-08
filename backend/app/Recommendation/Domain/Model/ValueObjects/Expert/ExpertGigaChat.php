<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Expert;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Model\ValueObjects\GigaChat\ContextValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GigaChat\ModelValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GigaChat\PromptValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GigaChat\StreamValueObject;

class ExpertGigaChat implements RecommendationExpertInterface
{
    private ModelValueObject $model;
    private PromptValueObject $prompt;
    private ContextValueObject $context;
    private StreamValueObject $stream;
    private array $taskData;

    public function __construct()
    {
    }

    private function init(array $taskData, bool|null $isPostCondition): void
    {
        $this->initTaskData($taskData);
        $this->initModel();
        $this->initPrompt($isPostCondition);
        $this->initContext();
        $this->initStream();
    }

    private function initTaskData(array $taskData): void
    {
        $this->taskData = $taskData;
    }

    private function initModel(): void
    {
        $this->model = new ModelValueObject();
    }

    private function initPrompt(bool|null $isPostCondition): void
    {
        $this->prompt = new PromptValueObject($this->taskData, $isPostCondition);
    }

    private function initContext(): void
    {
        $this->context = new ContextValueObject();
    }

    private function initStream(): void
    {
        $this->stream = new StreamValueObject();
    }

    public function getMessage(array $taskData, bool|null $isPostCondition = null): array
    {
        $this->init($taskData, $isPostCondition);

        return $this->formMessage();
    }

    private function formMessage(): array
    {
        return [
            'model' => $this->model->getModel(),
            'messages' => array_merge($this->context->getContext(), $this->prompt->getPrompt()),
            'stream' => $this->stream->getStream()
        ];
    }
}