<?php

namespace App\Recommendation\Domain\Contracts\ValueObjects\Expert;

interface RecommendationExpertInterface
{
    public function getMessage(array $taskData, bool|null $isPostCondition = null);
}
