<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

class NullClientWrapper implements ClientWrapper
{
    /** @inheritDoc */
    public function request(string $method, string $uri, array $options = []): array
    {
        return [];
    }
}
