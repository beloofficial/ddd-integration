<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

interface PaymentGatewayProvider
{
    public function get(string $key): PaymentGatewayInterface;
}