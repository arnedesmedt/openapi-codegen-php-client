<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

interface ClientWrapper
{
    /**
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    public function request(string $method, string $uri, array $options = []): array;
}
