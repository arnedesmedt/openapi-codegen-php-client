<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use Client\ClientWrapper;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class SymfonyClientWrapper implements ClientWrapper
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function client(): HttpClientInterface
    {
        return $this->client;
    }

    /** @inheritDoc */
    public function request(string $method, string $uri, array $options = []): array
    {
        $response = $this->client()->request($method, $uri, $options);

        return $this->contentFromResponse($response);
    }

    /** @return array<mixed> */
    private function contentFromResponse(ResponseInterface $response): array
    {
        assert($response instanceof ResponseInterface);

        $responseContent = $response->getContent();

        /** @var array<mixed> $content */
        $content = json_decode($responseContent, true, 512, JSON_THROW_ON_ERROR);

        return $content;
    }
}
