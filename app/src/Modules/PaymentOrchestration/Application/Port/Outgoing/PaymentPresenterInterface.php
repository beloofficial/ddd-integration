<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;

interface PaymentPresenterInterface
{
    public function presentSuccess(Transaction $transaction): void;

    public function presentSystemError(string $message): void;
}