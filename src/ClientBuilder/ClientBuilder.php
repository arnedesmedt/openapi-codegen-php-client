<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\ClientBuilder;

use ADS\OpenApi\Codegen\Client\Client;
use ADS\OpenApi\Codegen\Endpoint\Builder as EndpointBuilder;
use RuntimeException;

use function file_exists;
use function file_get_contents;
use function json_decode;
use function sprintf;

/**
 * A base client builder implementation.
 */
abstract class ClientBuilder
{
    /**
     * Return the configured client.
     */
    abstract public function build(): Client;

    /** @return array<string, mixed> */
    protected function configs(string $path): array
    {
        if (! file_exists($path)) {
            throw new RuntimeException(
                sprintf('Could not load configs for path \'%s\'.', $path),
            );
        }

        /** @var string $content */
        $content = file_get_contents($path);

        /** @var array<string, mixed> $configs */
        $configs = json_decode($content, true);

        return $configs;
    }

    abstract protected function endpointBuilder(): EndpointBuilder;
}
