<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

interface ApiServiceConfig
{
    public function host(): string;

    public function token(): string;
}