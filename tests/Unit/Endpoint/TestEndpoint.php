<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Endpoint;

use ADS\OpenApi\Codegen\Endpoint\Endpoint;
use ADS\OpenApi\Codegen\Endpoint\EndpointLogic;

class TestEndpoint implements Endpoint
{
    use EndpointLogic;

    private string $method = 'GET';

    private string $uri = '/test/{idTest}';

    private int $idTest;
    private TestQuery|null $query = null;
    private TestData|null $body = null;
    private TestData|null $form = null;
}
