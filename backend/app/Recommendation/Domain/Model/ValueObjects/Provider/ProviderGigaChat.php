<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Provider;

use App\Recommendation\Domain\Contracts\ValueObjects\Provider\RecommendationProviderInterface;

class ProviderGigaChat implements RecommendationProviderInterface
{
    private string $apiUrl;

    private string $token;

    public function __construct()
    {
        $this->init();
        $this->getAccessToken();
    }

    private function init(): void
    {
        $this->initApiUrl();
    }

    private function initApiUrl(): void
    {
        $this->apiUrl = config('gigachat.api_url');
    }

    private function getAccessToken(): void
    {
        $url = config('gigachat.token_url');
        $headers = [
            'Authorization: Basic '.base64_encode(
                config('gigachat.client_id').':'.config('gigachat.client_secret')
            ),
            'RqUID: '.config('gigachat.rquid'),
            'Content-Type: application/x-www-form-urlencoded',
        ];
        $data = [
            'scope' => 'GIGACHAT_API_PERS',
        ];
        $result = $this->getResult($url, $headers, http_build_query($data));

        if (! empty($result['access_token'])) {
            $this->token = $result['access_token'];
        } else {
            throw new \DomainException('Invalid token: received null token');
        }
    }

    private function getResult($url, $headers, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => 1,
        ]);
        if (! empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $result = curl_exec($curl);

        return json_decode($result, true);
    }

    /**
     * @throws \DomainException
     * @throws \Exception
     */
    public function getSuggestion($query): Result
    {
        $response_data = $this->getResult($this->apiUrl, $this->prepareHeaders(), json_encode($query));

        if (! isset($response_data['choices'][0]['message']['content'])) {
            throw new \DomainException('Invalid response format: content is missing');
        }

        $response = explode('###', $response_data['choices'][0]['message']['content']);

        if (count($response) < 2) {
            throw new \DomainException('Invalid response format: expected 2 parts, but received '.count($response));
        }

        return new Result(new SmartTitle($response[0]), new Recommendation($response[1]));
    }

    private function prepareHeaders(): array
    {
        return [
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token,
        ];
    }
}
