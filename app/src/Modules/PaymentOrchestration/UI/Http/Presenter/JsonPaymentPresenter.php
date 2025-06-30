<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\UI\Http\Presenter;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentPresenterInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonPaymentPresenter implements PaymentPresenterInterface
{
    private array $data = [];

    public function presentSuccess(Transaction $transaction): void
    {
        $this->data = [
            'status'        => 'success',
            'transactionId' => $transaction->transactionId,
            'createdAt'     => $transaction->createdAt->format('Y-m-d H:i:s'),
            'amount'        => $transaction->money->amount,
            'currency'      => $transaction->money->currency,
            'cardBin'       => $transaction->cardBin,
        ];
    }

    public function presentSystemError(string $message): void
    {
        $this->data = [
            'status'  => 'error',
            'message' => $message,
        ];
    }

    public function view(): JsonResponse
    {
        return new JsonResponse($this->data);
    }
}