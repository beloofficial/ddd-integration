<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway\Aci;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\HttpApiInterface;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Card;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use Exception;
use Throwable;

final class AciGateway implements PaymentGatewayInterface
{
    public function __construct(
        private readonly HttpApiInterface $api,
        private readonly AciGatewayResponseFactory $resultFactory,
    ) {
    }

    /**
     * @throws Exception
     */
    public function pay(Money $money, Card $card): Transaction
    {
        $body = [
            'entityId'         => '8ac7a4c79394bdc801939736f17e063d',
            'amount'           => $money->amount,
            'currency'         => $money->currency,
            'paymentBrand'     => 'VISA',
            'paymentType'      => 'DB',
            'card.number'      => $card->number,
            'card.holder'      => 'John Doe',
            'card.expiryMonth' => sprintf('%02d', $card->expMonth),
            'card.expiryYear'  => $card->expYear,
            'card.cvv'         => $card->cvv,
        ];

        $result = $this->send($body);

        return new Transaction(
            transactionId: $result->transactionId,
            createdAt: $result->created,
            money: new Money($result->amount, $money->currency),
            cardBin: $card->bin()
        );
    }

    /**
     * @throws Exception
     */
    protected function send(array $body): AciGatewayResponse
    {
        try {
            $result = $this->api->send(
                'POST',
                '/payments',
                $body
            );

            return $this->resultFactory->createFromResponse($result);
        } catch (Throwable $throwable) {
            throw new Exception(
                'Failed to send pay request to ACI PS: ' . $throwable->getMessage(),
                500
            );
        }
    }
}