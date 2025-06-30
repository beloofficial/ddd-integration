<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Domain\Model\ValueObject;

final class Money
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency
    ) {}

    public function __toString(): string
    {
        return sprintf('%d %s', $this->amount, $this->currency);
    }
}