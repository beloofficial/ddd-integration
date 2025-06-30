<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Gateway;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayInterface;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\PaymentGatewayProvider;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class PaymentGatewayRegistry implements PaymentGatewayProvider
{
    /** @var array<string,PaymentGatewayInterface> */
    private array $gateways;

    public function __construct(
        #[TaggedIterator(tag: 'payment.gateway', indexAttribute: 'alias')]
        iterable $gateways,
    ) {
        $this->gateways = iterator_to_array($gateways);
    }

    public function get(string $key): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$key])) {
            throw new InvalidArgumentException("Unsupported gateway: $key");
        }
        return $this->gateways[$key];
    }
}
