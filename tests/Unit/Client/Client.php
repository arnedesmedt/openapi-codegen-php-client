<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

use ADS\OpenApi\Codegen\Tests\Unit\Endpoint\TestEndpoint;

class Client extends \ADS\OpenApi\Codegen\Client\Client
{
    public function test(): mixed
    {
        return $this->performRequest(
            TestEndpoint::fromArray(
                [
                    'idTest' => 1,
                    'query' => ['testQuery' => 'ziezo'],
                ],
            ),
        );
    }
}
