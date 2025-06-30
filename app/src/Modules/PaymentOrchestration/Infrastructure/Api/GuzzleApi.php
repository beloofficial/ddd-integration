<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Api;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\ApiServiceConfig;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\ApiServiceConfigProvider;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\HttpApiInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

final class GuzzleApi implements HttpApiInterface
{
    private ApiServiceConfig $config;

    public function __construct(
        private readonly Client $client,
        private readonly ApiServiceConfigProvider $configProvider,
    ) {
        $this->config = $this->configProvider->getConfig();
    }

    public function send(string $method, string $url, ?array $data = []): Result
    {
        $headers = [
            'Authorization' => $this->config->token(),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        $options = [
            RequestOptions::FORM_PARAMS => $data,
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS     => $headers,
        ];

        $request = new Request($method, $this->getURI($url), $headers);

        $response = $this->client->send($request, $options);

        return new Result($request, $response);
    }

    /**
     * @throws Exception
     */
    private function getURI(string $url): string
    {
        return rtrim($this->config->host(), '/') . $url;
    }
}
