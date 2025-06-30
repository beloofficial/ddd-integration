<?php

declare(strict_types=1);

namespace App\Modules\PaymentOrchestration\Infrastructure\Api;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Result
{
    public function __construct(
        public RequestInterface $request,
        public ResponseInterface $response,
    ) {
    }
}
