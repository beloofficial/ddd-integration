<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Config\Aci;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\ApiServiceConfig as AppApiServiceConfig;
use Exception;

class ApiServiceConfig implements AppApiServiceConfig
{
    public function __construct(
        private readonly ?string $host,
        private readonly ?string $token,
    ) {
    }

    /**
     * @throws Exception
     */
    public function host(): string
    {
        return $this->host ?? throw new Exception(
            'The Host is not set for Payment Orchestration API.'
        );
    }

    /**
     * @throws Exception
     */
    public function token(): string
    {
        return $this->token
            ? "Bearer {$this->token}"
            : throw new Exception('The token is not set for Payment Orchestration API.');
    }
}
