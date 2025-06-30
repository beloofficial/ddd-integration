<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Domain\Model\ValueObject;

final class Card
{
    public function __construct(
        public readonly string $number,
        public readonly int    $expMonth,
        public readonly int    $expYear,
        public readonly string $cvv
    ) {}

    public function bin(): string
    {
        return substr($this->number, 0, 6);
    }
}