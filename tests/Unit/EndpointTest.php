<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit;

use ADS\OpenApi\Codegen\Endpoint\Builder;
use ADS\OpenApi\Codegen\Endpoint\Endpoint;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    private Builder $endpointBuilder;

    public function setUp(): void
    {
        $this->endpointBuilder = new Builder(__NAMESPACE__ . '\Endpoint');
    }

    public function testTestEndpoint(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setPathParameters(['idTest' => 1]);
        $endpoint->setQueryParameters(['testQuery' => 'test']);

        $this->assertInstanceOf(Endpoint::class, $endpoint);
        $this->assertEquals('GET', $endpoint->method());
        $this->assertEquals('test/1', $endpoint->uri());
        $this->assertEquals(['idTest' => 1], $endpoint->pathParameters());
    }

    public function testNotAllowedQueryParameter(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid parameter. Allowed parameters are/');
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setQueryParameters(['testQuery2' => 'test']);
    }

    public function testNotAllowedArrayQueryParameter(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid parameter. Allowed parameters are/');
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setQueryParameters(['arrayQuery[]' => 'test']);
    }

    public function testAllowedArrayQueryParameter(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setQueryParameters(['arrayQuery' => ['test', 'test2']]);

        $this->assertEquals(['test', 'test2'], $endpoint->queryParameters()['arrayQuery']);
    }

    public function testNotAllowedPathParameter(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid parameter. Allowed parameters are/');
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setPathParameters(['idTest2' => 1]);
    }

    public function testSetNullAsQueryParameter(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setQueryParameters(null);

        $this->assertIsArray($endpoint->queryParameters());
        $this->assertEmpty($endpoint->queryParameters());
    }

    public function testSetNullAsPathParameter(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setPathParameters(null);

        $this->assertIsArray($endpoint->pathParameters());
        $this->assertEmpty($endpoint->pathParameters());
    }

    public function testSetBody(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setBody(
            [
                'test' => 'test',
                'test-null' => null,
                'test-empty-array' => [],
            ],
        );

        $this->assertEquals(['test' => 'test'], $endpoint->body());
    }

    public function testSetBodyNull(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setBody(null);

        $this->assertNull($endpoint->body());
    }

    public function testSetFormData(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setFormData(
            [
                'test' => 'test',
                'test-null' => null,
                'test-empty-array' => [],
            ],
        );

        $this->assertEquals(['test' => 'test'], $endpoint->formData());
    }

    public function testSetFormDataNull(): void
    {
        $endpoint = ($this->endpointBuilder)('TestEndpoint');
        $endpoint->setFormData(null);

        $this->assertNull($endpoint->formData());
    }
}
