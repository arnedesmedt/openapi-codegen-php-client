<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Client;

use ADS\ClientMock\Mock;
use ADS\ClientMock\MockPersister;
use ADS\OpenApi\Codegen\ClientBuilder\ClientBuilder;
use Unit\Client\ClientMock;

class ClientBuilderWithNonExistingConfig extends ClientBuilder
{
    public function build(): Client
    {
        return new Client(
            $this->client(),
            $this->configs(__DIR__ . '/config-non-exist.json'),
        );
    }

    public static function mock(): Mock
    {
        return new ClientMock(new MockPersister());
    }
}
