<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit;

use ADS\OpenApi\Codegen\Client\NullClientWrapper;
use ADS\OpenApi\Codegen\Tests\Unit\Client\Client;
use ADS\OpenApi\Codegen\Tests\Unit\Client\ClientBuilder;
use ADS\OpenApi\Codegen\Tests\Unit\Client\ClientBuilderWithNonExistingConfig;
use PHPUnit\Framework\TestCase;

class ClientBuilderTest extends TestCase
{
    public function testBuildClient(): void
    {
        $clientBuilder = new ClientBuilder(new NullClientWrapper());

        $this->assertInstanceOf(ClientBuilder::class, $clientBuilder);

        $client = $clientBuilder->build();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testBuildClientWithNonExistingConfig(): void
    {
        $this->expectExceptionMessageMatches('/Could not load configs for path/');
        $clientBuilder = new ClientBuilderWithNonExistingConfig(new NullClientWrapper());
        $clientBuilder->build();
    }
}
