<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

interface ApiServiceConfigProvider
{
    public function getConfig(): ApiServiceConfig;
}
