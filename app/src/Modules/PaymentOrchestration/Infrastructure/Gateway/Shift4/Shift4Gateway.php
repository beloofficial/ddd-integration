<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway\Shift4;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\HttpApiInterface;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Card;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use DateTimeImmutable;
use Exception;
use Throwable;

final class Shift4Gateway implements PaymentGatewayInterface
{
    public function __construct(
        private readonly HttpApiInterface $api,
        private readonly Shift4GatewayResponseFactory $resultFactory,
    ) {
    }

    /**
     * @throws Exception
     */
    public function pay(Money $money, Card $card): Transaction
    {
        $body = [
            'amount'        => $money->amount,
            'currency'      => $money->currency,
            'customerId'  => 'cust_jCgGLDVvj9hO8o0z4L3oIIvT',
        ];

        $result = $this->send($body);

        return new Transaction(
            transactionId: $result->transactionId,
            createdAt: new DateTimeImmutable('@' . $result->created),
            money: new Money($result->amount, $money->currency),
            cardBin: $card->bin()
        );
    }

    /**
     * @throws Exception
     */
    protected function send(array $body): Shift4GatewayResponse
    {
        try {
            $result = $this->api->send(
                'POST',
                '/charges',
                $body
            );

            return $this->resultFactory->createFromResponse($result);
        } catch (Throwable $throwable) {
            throw new Exception(
                'Failed to send pay request to Shift4 PS: ' . $throwable->getMessage(),
                500
            );
        }
    }
}