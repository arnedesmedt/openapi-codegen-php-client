<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\ClientBuilder;

use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class SymfonyClientBuilder extends ClientBuilder
{
    protected HttpClientInterface|null $client = null;

    protected function client(): HttpClientInterface
    {
        if ($this->client === null) {
            throw new RuntimeException(
                'Couldn\'t create a connection if no guzzle client is set.',
            );
        }

        return $this->client;
    }

    public function setClient(HttpClientInterface $client): static
    {
        $this->client = $client;

        return $this;
    }
}
