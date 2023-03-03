<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit;

use ADS\OpenApi\Codegen\Endpoint\Endpoint;
use ADS\OpenApi\Codegen\Tests\Unit\Endpoint\TestData;
use ADS\OpenApi\Codegen\Tests\Unit\Endpoint\TestEndpoint;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    public function testMethodAndUri(): void
    {
        $endpoint = TestEndpoint::fromArray(
            [
                'idTest' => 1,
                'query' => ['testQuery' => 'test'],
            ],
        );

        $this->assertInstanceOf(Endpoint::class, $endpoint);
        $this->assertEquals('GET', $endpoint->method());
        $this->assertEquals('test/1', $endpoint->uri());
        $this->assertEquals('/test/{idTest}', $endpoint->uriTemplate());
    }

    public function testQuery(): void
    {
        $endpoint = TestEndpoint::fromArray(
            [
                'idTest' => 1,
                'query' => ['arrayQuery[]' => ['test', 'test2']],
            ],
        );

        $this->assertEquals(['test', 'test2'], $endpoint->query()?->toArray()['arrayQuery[]']);
    }

    public function testBody(): void
    {
        $endpoint = TestEndpoint::fromArray(
            [
                'idTest' => 1,
                'body' => ['test' => 'test'],
            ],
        );

        $body = $endpoint->body();

        $this->assertInstanceOf(TestData::class, $body);
        $this->assertEquals('test', $body->toArray()['test']);
    }

    public function testForm(): void
    {
        $endpoint = TestEndpoint::fromArray(
            [
                'idTest' => 1,
                'form' => ['test' => 'test'],
            ],
        );

        $form = $endpoint->form();

        $this->assertInstanceOf(TestData::class, $form);
        $this->assertEquals('test', $form->toArray()['test']);
    }
}
