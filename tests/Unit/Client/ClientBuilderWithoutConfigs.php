<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

use ADS\OpenApi\Codegen\ClientBuilder\ClientBuilder;

class ClientBuilderWithoutConfigs extends ClientBuilder
{
    public function build(): Client
    {
        return new Client(
            $this->client(),
            [],
        );
    }
}
