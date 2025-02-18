<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;

class ProviderLlama implements RecommendationProviderInterface
{
    private string $apiUrl;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initApiUrl();
    }

    private function initApiUrl(): void
    {
        $this->apiUrl = config("llama.url");
    }

    public function getSuggestion($query): ProviderResponse
    {
        $response_data = $this->getResult($query);

        if (!isset($response_data["message"]["content"])) {
            throw new \DomainException("Invalid response format: content is missing");
        }

        $response = json_decode($response_data["message"]["content"], true);

        if (!$response["smartTitle"] || !$response["recommendation"]) {
            throw new \DomainException("Invalid response format: empty required fields");
        }

        return new ProviderResponse(new SmartTitle($response["smartTitle"]), new Recommendation($response["recommendation"]));
    }

    /**
     * @throws \DomainException
     */
    private function getResult($query): array
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->prepareHeaders());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new \DomainException('Failed to get response from language model');
        }

        if ($httpCode !== 200) {
            throw new \DomainException("Language model returned an invalid status code: $httpCode");
        }

        return json_decode($response, true);
    }

    private function prepareHeaders(): array
    {
        return [
            'Content-Type: application/json',
        ];
    }
}
