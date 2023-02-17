<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use Client\ClientWrapper;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Http\Message\ResponseInterface;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class GuzzleClientWrapper implements ClientWrapper
{
    public function __construct(
        private readonly GuzzleHttpClient $client,
    ) {
    }

    public function client(): GuzzleHttpClient
    {
        return $this->client;
    }

    /** @inheritDoc */
    public function request(string $method, string $uri, array $options = []): array
    {
        $response = $this->client()->request($method, $uri, $options);

        return $this->contentFromResponse($response);
    }

    /**
     * *
     *
     * @return array<mixed>
     */
    private function contentFromResponse(ResponseInterface $response): array
    {
        assert($response instanceof ResponseInterface);

        $response->getBody()->rewind();
        $contents = $response->getBody()->getContents();

        /** @var array<mixed> $content */
        $content = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        return $content;
    }
}
