<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Endpoint;

use ADS\OpenApi\Codegen\Endpoint\AbstractEndpoint;

class TestEndpoint extends AbstractEndpoint
{
    protected string $method = 'GET';

    protected string $uri = '/test/{idTest}';

    /** @var array<string> */
    protected array $pathParameterNames = ['idTest'];

    /** @var array<string> */
    protected array $queryParameterNames = [
        'testQuery',
        'arrayQuery[]',
    ];
}
