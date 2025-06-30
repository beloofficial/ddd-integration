<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Gateway\Aci;

use App\Modules\PaymentOrchestration\Infrastructure\Api\Result;
use App\Modules\PaymentOrchestration\Infrastructure\Gateway\Aci\AciGatewayResponse;
use App\Modules\PaymentOrchestration\Infrastructure\Gateway\Aci\AciGatewayResponseFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class AciGatewayTest extends TestCase
{
    public function testPaySuccess(): void
    {
        $json = json_encode([
            'id' => '8ac7a49f97bdb3140197be66c1e90f78',
            'timestamp' => '2025-06-30 01:14:55.074+0000',
            'amount' => '1000',
            'currency' => 'EUR',
        ]);

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getBody')->willReturn($this->createStreamMock($json));
        $mockResponse->method('getStatusCode')->willReturn(200);

        $mockRequest = $this->createMock(RequestInterface::class);
        $result = new Result($mockRequest, $mockResponse);

        $factory = new AciGatewayResponseFactory();
        $response = $factory->createFromResponse($result);

        $this->assertInstanceOf(AciGatewayResponse::class, $response);
        $this->assertSame('8ac7a49f97bdb3140197be66c1e90f78', $response->transactionId);
        $this->assertSame(
            '2025-06-30T01:14:55+00:00',
            $response->created->format(DATE_ATOM)
        );
        $this->assertSame(1000, $response->amount);
        $this->assertSame('EUR', $response->currency);
    }

    public function testMissingIdThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('id not provided');

        $json = json_encode([
            'timestamp' => '2024-01-01T00:00:00Z',
            'amount'    => 1000,
            'currency'  => 'EUR',
        ]);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($this->createStreamMock($json));
        $response->method('getStatusCode')->willReturn(200);

        $request = $this->createMock(RequestInterface::class);
        $result  = new Result($request, $response);

        $factory = new AciGatewayResponseFactory();
        $factory->createFromResponse($result);
    }

    private function createStreamMock(string $contents)
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($contents);
        return $stream;
    }
}
