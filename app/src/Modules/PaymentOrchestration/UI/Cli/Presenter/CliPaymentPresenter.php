<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Cli\Presenter;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentPresenterInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CliPaymentPresenter implements PaymentPresenterInterface
{
    private SymfonyStyle $io;

    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    public function presentSuccess(Transaction $transaction): void
    {
        $this->io->success('Payment authorised');
        $this->io->table(
            ['TransactionId', 'Created', 'Amount', 'Currency', 'Card BIN'],
            [[
                 $transaction->transactionId,
                 $transaction->createdAt->format('Y-m-d H:i:s'),
                 $transaction->money->amount,
                 $transaction->money->currency,
                 $transaction->cardBin,
             ]]
        );
    }

    public function presentSystemError(string $message): void
    {
        $this->io->error("System error: $message");
    }
}