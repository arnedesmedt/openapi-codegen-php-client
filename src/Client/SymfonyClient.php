<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use Closure;
use Exception\SymfonyExceptionHandler;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

abstract class SymfonyClient extends Client
{
    /** @param array<string, mixed> $configs */
    public function __construct(
        private HttpClientInterface $client,
        Closure $endpointBuilder,
        array $configs,
    ) {
        parent::__construct($endpointBuilder, $configs);
    }

    public function client(): HttpClientInterface
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

        $responseContent = $response->getContent();

        /** @var array<mixed> $content */
        $content = json_decode($responseContent, true, 512, JSON_THROW_ON_ERROR);

        return $content;
    }

    protected function handleException(Throwable $exception): Throwable
    {
        if (! $exception instanceof ClientException) {
            return $exception;
        }

        return SymfonyExceptionHandler::fromClientException(
            $exception,
            $this->exceptionHandler,
        );
    }
}
