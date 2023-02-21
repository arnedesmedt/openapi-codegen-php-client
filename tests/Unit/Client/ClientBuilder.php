<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

class ClientBuilder extends \ADS\OpenApi\Codegen\ClientBuilder\ClientBuilder
{
    public function build(): Client
    {
        return new Client(
            $this->client(),
            $this->configs(__DIR__ . '/config.json'),
        );
    }
}
