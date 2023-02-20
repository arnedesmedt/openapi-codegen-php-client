<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

use ADS\OpenApi\Codegen\Endpoint\Builder as EndpointBuilder;

class ClientBuilder extends \ADS\OpenApi\Codegen\ClientBuilder\ClientBuilder
{
    public function build(): Client
    {
        return new Client(
            $this->client(),
            $this->endpointBuilder(),
            $this->configs(__DIR__ . '/config.json'),
        );
    }

    protected function endpointBuilder(): EndpointBuilder
    {
        return new EndpointBuilder('ADS\OpenApi\Codegen\Tests\Unit\Endpoint');
    }
}
