<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Config\Shift4;

use App\Modules\PaymentOrchestration\Application\Port\Outgoing\ApiServiceConfig as AppApiServiceConfig;
use App\Modules\PaymentOrchestration\Application\Port\Outgoing\ApiServiceConfigProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class EnvApiServiceProvider implements ApiServiceConfigProvider
{
    private AppApiServiceConfig $config;

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function getConfig(): AppApiServiceConfig
    {
        return $this->config ??= new ApiServiceConfig(
            $this->parameterBag->get('shift4.api_host'),
            $this->parameterBag->get('shift4.api_token'),
        );
    }
}
