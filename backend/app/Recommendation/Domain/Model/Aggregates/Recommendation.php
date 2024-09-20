<?php

namespace App\Recommendation\Domain\Model\Aggregates;

use App\Recommendation\Domain\Contracts\ValueObjects\Expert\RecommendationExpertInterface;
use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class Recommendation
{
//    private Message $message;
    private array $message;
    private array $suggestion;
//    private Suggestion $suggestion;

    private RecommendationExpertInterface $expert;
    private RecommendationProviderInterface $provider;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        public ?int $id,
        public array $query,
        public ?string $answer = null,
    ) {
        $this->initDependencies();
        $this->initMessage();
        $this->initAnswer();
    }

    public function suggest()
    {
        return $this->suggestion;
        $this->process();
    }

    /**
     * @throws BindingResolutionException
     */
    private function initDependencies(): void
    {
        $this->expert = app()->make(
            RecommendationExpertInterface::class,
            [
                'taskData' => json_decode(html_entity_decode($this->query['payload']), true),
                'isPostCondition' => true
            ]
        );
        $this->provider = app()->make(RecommendationProviderInterface::class);
    }

    private function initMessage(): void
    {
        $this->message = $this->expert->getMessage();
    }

    private function initAnswer(): void
    {
        $this->suggestion = $this->provider->getSuggestion($this->message);
    }

    private function process()
    {
        // Save suggestion
        // Return $this
    }
}
