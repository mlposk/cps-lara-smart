<?php

namespace App\Recommendation\Domain\Contracts\ValueObjects\Provider;

interface RecommendationProviderInterface
{
    public function getSuggestion($query);
}
