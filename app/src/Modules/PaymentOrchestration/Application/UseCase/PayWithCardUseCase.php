<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\UseCase;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayProvider;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentPresenterInterface;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Card;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use App\Modules\PaymentOrchestration\UI\Http\Request\PaymentRequest;
use Exception;

final class PayWithCardUseCase
{
    public function __construct(
        private readonly PaymentGatewayProvider $provider,
        private readonly PaymentPresenterInterface $presenter,
    ) {
    }

    public function execute(string $gatewayKey, PaymentRequest $request): void
    {
        try {
            $money = new Money($request->amount, $request->currency);
            $card  = new Card($request->card, $request->expMonth, $request->expYear, $request->cvv);

            $transaction = $this->provider
                ->get($gatewayKey)
                ->pay($money, $card);

            $this->presenter->presentSuccess($transaction);
        } catch (Exception $e) {
            $this->presenter->presentSystemError($e->getMessage());
        }
    }
}
