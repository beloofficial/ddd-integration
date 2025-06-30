<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Presenter;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentPresenterInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class DelegatingPaymentPresenter implements PaymentPresenterInterface
{
    /** @var array<string,PaymentPresenterInterface> */
    private array $presenters;

    public function __construct(
        #[TaggedIterator(tag: 'payment.presenter', indexAttribute: 'context')]
        iterable $presenters,
    ) {
        $this->presenters = iterator_to_array($presenters);
    }

    public function presentSuccess(Transaction $transaction): void
    {
        $this->pick()->presentSuccess($transaction);
    }

    public function presentSystemError(string $message): void
    {
        $this->pick()->presentSystemError($message);
    }

    private function pick(): PaymentPresenterInterface
    {
        $context = \PHP_SAPI === 'cli' ? 'cli' : 'http';

        if (!isset($this->presenters[$context])) {
            throw new InvalidArgumentException("No presenter registered for context: $context");
        }
        return $this->presenters[$context];
    }
}
