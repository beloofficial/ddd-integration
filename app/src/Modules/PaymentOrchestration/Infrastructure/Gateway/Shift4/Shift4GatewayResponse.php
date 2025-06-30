<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway\Shift4;

final class Shift4GatewayResponse
{
    public function __construct(
        public string $transactionId,
        public int $created,
        public int $amount,
        public string $currency,
    ) {}
}
