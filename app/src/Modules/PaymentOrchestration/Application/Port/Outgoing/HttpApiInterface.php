<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Application\Port\Outgoing;

use App\Modules\PaymentOrchestration\Infrastructure\Api\Result;
use Exception;

interface HttpApiInterface
{
    public function send(string $method, string $url, ?array $data = []): Result;
}
