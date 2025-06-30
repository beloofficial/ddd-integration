<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Domain\Model\Aggregate;

use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use DateTimeImmutable;

final class Transaction
{
    public function __construct(
        public readonly string $transactionId,
        public readonly DateTimeImmutable $createdAt,
        public readonly Money  $money,
        public readonly string $cardBin,
    ) {}
}