<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Card;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;

interface PaymentGatewayInterface
{
    public function pay(Money $money, Card $card): Transaction;
}