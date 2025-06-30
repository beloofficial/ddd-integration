<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway\Aci;

use DateTimeImmutable;

final class AciGatewayResponse
{
    public DateTimeImmutable $created;

    public int $amount;

    public function __construct(
        public string $transactionId,
        string $created,
        string $amount,
        public string $currency,
    ) {
        $this->created = new DateTimeImmutable($created);
        $this->amount = (int) $amount;
    }
}
