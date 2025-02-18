<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Expert;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Model\ValueObjects\Llama\Context;
use App\Recommendation\Domain\Model\ValueObjects\Llama\Model;
use App\Recommendation\Domain\Model\ValueObjects\Llama\Prompt;
use App\Recommendation\Domain\Model\ValueObjects\Llama\Stream;
use App\Recommendation\Domain\Model\ValueObjects\Llama\ResponseFormat;

class ExpertLlama implements RecommendationExpertInterface
{
    private Model $model;

    private Prompt $prompt;

    private Context $context;

    private Stream $stream;

    private ResponseFormat $responseFormat;

    private array $taskData;

    public function __construct()
    {
    }

    private function init(array $taskData, ?bool $isPostCondition): void
    {
        $this->initTaskData($taskData);
        $this->initModel();
        $this->initPrompt($isPostCondition);
        $this->initContext();
        $this->initStream();
        $this->initResponseFormat();
    }

    private function initTaskData(array $taskData): void
    {
        $this->taskData = $taskData;
    }

    private function initModel(): void
    {
        $this->model = new Model();
    }

    private function initPrompt(?bool $isPostCondition): void
    {
        $this->prompt = new Prompt($this->taskData, $isPostCondition);
    }

    private function initContext(): void
    {
        $this->context = new Context();
    }

    private function initStream(): void
    {
        $this->stream = new Stream();
    }

    private function initResponseFormat(): void
    {
        $this->responseFormat = new ResponseFormat();
    }

    public function getMessage(array $taskData, ?bool $isPostCondition = false): array
    {
        $this->init($taskData, $isPostCondition);

        return $this->formMessage();
    }

    private function formMessage(): array
    {
        return [
            "model" => $this->model->getModel(),
            "messages" => array_merge($this->context->getContext(), $this->prompt->getPrompt()),
            "stream" => $this->stream->getStream(),
            "format" => $this->responseFormat->getFormat()
        ];
    }
}
