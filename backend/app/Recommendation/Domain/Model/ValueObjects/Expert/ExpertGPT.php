<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Expert;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Model\ValueObjects\GPT\ContextValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GPT\ModelValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GPT\PromptValueObject;
use App\Recommendation\Domain\Model\ValueObjects\GPT\StreamValueObject;

class ExpertGPT implements RecommendationExpertInterface
{
    private ModelValueObject $model;
    private PromptValueObject $prompt;
    private ContextValueObject $context;
    private StreamValueObject $stream;
    private array $taskData;

    public function __construct(array $taskData, bool|null $isPostCondition = null)
    {
        $this->taskData = $taskData;
        $this->init($isPostCondition);
    }

    private function init(bool|null $isPostCondition): void
    {
        $this->initModel();
        $this->initPrompt($isPostCondition);
        $this->initContext();
        $this->initStream();
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

    public function getMessage(): array
    {
        return [
            'model' => $this->model->getModel(),
            'messages' => array_merge($this->context->getContext(), $this->prompt->getPrompt()),
            'stream' => $this->stream->getStream()
        ];
    }
}
