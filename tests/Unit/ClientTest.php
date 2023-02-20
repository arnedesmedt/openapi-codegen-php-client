<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit;

use ADS\OpenApi\Codegen\Client\NullClientWrapper;
use ADS\OpenApi\Codegen\Tests\Unit\Client\ClientBuilder;
use ADS\OpenApi\Codegen\Tests\Unit\Client\ClientBuilderWithoutConfigs;
use PHPUnit\Framework\TestCase;

use function array_filter;

class ClientTest extends TestCase
{
    /** @return array<string, array<string, mixed>> */
    public static function requestResponseProvider(): array
    {
        return [
            'default' => [
                'response' => [],
                'return' => [],
            ],
            'hal' => [
                'response' => [
                    '_links' => [
                        'self' => ['href' => '/test/1'],
                    ],
                    '_embedded' => [
                        'author' => [
                            '_links' => [
                                'self' => ['href' => '/test/2'],
                            ],
                            'id' => 1,
                            'name' => 'test',
                            'homepage' => 'http://test.com',
                        ],
                    ],
                ],
                'return' => [
                    'id' => 1,
                    'name' => 'test',
                    'homepage' => 'http://test.com',
                ],
            ],
        ];
    }

    /**
     * @param array<mixed> $response
     * @param array<mixed> $return
     *
     * @dataProvider requestResponseProvider
     */
    public function testTestEndpoint(array $response, array $return): void
    {
        $wrapper = $this->createMock(NullClientWrapper::class);
        $options = [
            'query' => ['testQuery' => 'ziezo'],
            'body' => [],
        ];
        $wrapper
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'test/1',
                array_filter($options),
            )
            ->willReturn($response);

        $client = (new ClientBuilder($wrapper))->build();
        $clientResponse = $client->test();

        $this->assertEquals($return, $clientResponse);
    }

    public function testTestEndpointWithoutConfigs(): void
    {
        $client = (new ClientBuilderWithoutConfigs(new NullClientWrapper()))->build();
        $clientResponse = $client->test();

        $this->assertIsArray($clientResponse);
        $this->assertEmpty($clientResponse);
    }
}
