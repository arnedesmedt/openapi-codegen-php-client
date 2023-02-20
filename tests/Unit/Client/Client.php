<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

class Client extends \ADS\OpenApi\Codegen\Client\Client
{
    public function test(): mixed
    {
        return $this->performRequest(
            $this
                ->endpoint('TestEndpoint')
                ->setPathParameters(['idTest' => 1])
                ->setQueryParameters(['testQuery' => 'ziezo']),
        );
    }
}
