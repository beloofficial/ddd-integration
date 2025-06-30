<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\UseCase;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayInterface;
use App\Modules\PaymentOrchestration\Application\UseCase\PayWithCardUseCase;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayProvider;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentPresenterInterface;
use App\Modules\PaymentOrchestration\Domain\Model\Aggregate\Transaction;
use App\Modules\PaymentOrchestration\Domain\Model\ValueObject\Money;
use App\Modules\PaymentOrchestration\UI\Http\Request\PaymentRequest;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class PayWithCardUseCaseTest extends TestCase
{
    protected MockObject|PaymentPresenterInterface $presenter;

    protected MockObject|PaymentGatewayInterface $gateway;

    protected MockObject|PaymentGatewayProvider $provider;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->presenter = $this->createMock(PaymentPresenterInterface::class);
        $this->gateway = $this->createMock(PaymentGatewayInterface::class);
        $this->provider = $this->createMock(PaymentGatewayProvider::class);

    }
    public function testSuccessfulPayment(): void
    {
        $gatewayType = 'aci';

        $dto = PaymentRequest::fromArray([
            'amount'   => 1000,
            'currency' => 'EUR',
            'card'     => '4200000000000000',
            'expMonth' => 12,
            'expYear'  => 2038,
            'cvv'      => '123',
        ]);

        $transaction = new Transaction(
            transactionId: Uuid::uuid4()->toString(),
            createdAt:     new \DateTimeImmutable(),
            money:         new Money(1000, 'EUR'),
            cardBin:       '420000'
        );

        $this->presenter->expects(self::once())
                  ->method('presentSuccess')
                  ->with($transaction);

        $this->gateway->method('pay')
                ->willReturn($transaction);

        $this->provider->method('get')
                 ->with($gatewayType)
                 ->willReturn($this->gateway);

        $useCase = new PayWithCardUseCase($this->provider, $this->presenter);
        $useCase->execute($gatewayType, $dto);
    }
}
