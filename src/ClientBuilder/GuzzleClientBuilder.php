<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\ClientBuilder;

use GuzzleHttp\Client;
use RuntimeException;

abstract class GuzzleClientBuilder extends ClientBuilder
{
    protected Client|null $client = null;

    protected function client(): Client
    {
        if ($this->client === null) {
            throw new RuntimeException(
                'Couldn\'t create a connection if no guzzle client is set.',
            );
        }

        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
