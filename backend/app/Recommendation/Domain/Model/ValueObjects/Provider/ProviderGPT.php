<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;

class ProviderGPT implements RecommendationProviderInterface
{
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initApiKey();
        $this->initApiUrl();
    }

    private function initApiKey(): void
    {
        $this->apiKey = getenv('CHATGPT_API_KEY');
    }

    private function initApiUrl(): void
    {
        $this->apiUrl = getenv('CHATGPT_API_URL');
    }

    public function getSuggestion($query): array
    {
        $response_data = $this->getResult($query);
        $response = explode('%d%', $response_data['choices'][0]['message']['content']);
        return [
            'smart_title' => $response[0],
            'recommendation' => $response[1]
        ];
    }

    private function getResult($query): array
    {
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->prepareHeaders());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function prepareHeaders(): array
    {
        return [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key,
        ];
    }
}
