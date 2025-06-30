<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway\Aci;

use App\Modules\PaymentOrchestration\Infrastructure\Api\Result;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class AciGatewayResponseFactory
{
    private const UNKNOWN_ERROR = 'Unknown API error';

    /**
     * @throws Exception
     */
    public function createFromResponse(Result $result): AciGatewayResponse
    {
        $data = json_decode($result->response->getBody()->getContents(), true);

        if (is_array($data) === false) {
            throw new Exception('Unexpected response');
        }

        if ($result->response->getStatusCode() !== Response::HTTP_OK) {
            throw new Exception($data['result']['description'] ?? self::UNKNOWN_ERROR);
        }

        return new AciGatewayResponse(
            transactionId: $data['id'] ?? throw new Exception('id not provided'),
            created: $data['timestamp'] ?? throw new Exception('timestamp not provided'),
            amount: $data['amount'] ?? throw new Exception('amount not provided'),
            currency: $data['currency'] ?? throw new Exception('currency not provided'),
        );
    }
}
