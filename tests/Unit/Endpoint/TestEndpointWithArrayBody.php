<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Endpoint;

use ADS\OpenApi\Codegen\Endpoint\Endpoint;
use ADS\OpenApi\Codegen\Endpoint\EndpointLogic;

class TestEndpointWithArrayBody implements Endpoint
{
    use EndpointLogic;

    private string $method = 'GET';

    private string $uri = '/test-with-array-body/{idTest}';

    private int $idTest;
    /** @var array<mixed> $body */
    private array $body;

    /** @inheritDoc */
    public function toRequestParameters(): array
    {
        return [
            'idTest' => $this->idTest,
            'body' => $this->body,
        ];
    }
}
