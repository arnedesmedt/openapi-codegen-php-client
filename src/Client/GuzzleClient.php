<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use ADS\OpenApi\Codegen\Exception\GuzzleExceptionHandler;
use Closure;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

abstract class GuzzleClient extends Client
{
    /** @param array<string, mixed> $configs */
    public function __construct(
        private readonly GuzzleHttpClient $client,
        Closure $endpointBuilder,
        array $configs,
    ) {
        parent::__construct($endpointBuilder, $configs);
    }

    public function client(): GuzzleHttpClient
    {
        return $this->client;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array<mixed>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    protected function contentFromResponse($response): array
    {
        assert($response instanceof ResponseInterface);

        $response->getBody()->rewind();
        $contents = $response->getBody()->getContents();

        /** @var array<mixed> $content */
        $content = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        return $content;
    }

    protected function handleException(Throwable $exception): Throwable
    {
        if (! $exception instanceof ClientException) {
            return $exception;
        }

        return GuzzleExceptionHandler::fromClientException(
            $exception,
            $this->exceptionHandler,
        );
    }
}
