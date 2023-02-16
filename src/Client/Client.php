<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use ADS\OpenApi\Codegen\Endpoint\AbstractEndpoint;
use ADS\Util\ArrayUtil;
use Closure;
use GuzzleHttp\Client as GuzzleHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyClient;
use Throwable;

use function array_key_exists;
use function array_shift;
use function count;
use function is_array;

abstract class Client
{
    protected Closure|null $exceptionHandler = null;

    protected Closure|null $optionBuilder = null;

    private string|null $prependPath = null;

    /** @param array<string, mixed> $configs */
    public function __construct(
        private readonly Closure $endpointBuilder,
        private readonly array $configs = [],
    ) {
    }

    public function setExceptionHandler(Closure $exceptionHandler): static
    {
        $this->exceptionHandler = $exceptionHandler;

        return $this;
    }

    public function setOptionBuilder(Closure $optionBuilder): static
    {
        $this->optionBuilder = $optionBuilder;

        return $this;
    }

    public function setPrependPath(string $prependPath): static
    {
        $this->prependPath = $prependPath;

        return $this;
    }

    protected function endpoint(string $name): AbstractEndpoint
    {
        return ($this->endpointBuilder)($name);
    }

    /** @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint */
    abstract protected function client(): SymfonyClient|GuzzleHttpClient;

    protected function performRequest(AbstractEndpoint $endpoint): mixed
    {
        $method  = $endpoint->method();
        $uri     = $this->uriWithPrependPath($endpoint);
        $options = $this->buildOptions($endpoint);

        try {
            $response = $this->client()->request($method, $uri, $options);
        } catch (Throwable $exception) {
            throw $this->handleException($exception);
        }

        $content = $this->contentFromResponse($response);

        if ($this->configs['transformHal'] ?? false) {
            return $this->transformHal($content);
        }

        return $content;
    }

    /**
     * @return array<mixed>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    abstract protected function contentFromResponse(mixed $response): array;

    abstract protected function handleException(Throwable $exception): Throwable;

    /**
     * @param array<mixed> $content
     *
     * @return array<mixed>
     */
    private function transformHal(array $content): array
    {
        if (isset($content['_embedded']) && is_array($content['_embedded'])) {
            $content = array_shift($content['_embedded']);
        }

        unset($content['_links']);

        if (! ArrayUtil::isAssociative($content)) {
            foreach ($content as &$contentPart) {
                unset($contentPart['_links']);
            }
        }

        return $content;
    }

    protected function uriWithPrependPath(AbstractEndpoint $endpoint): string
    {
        $uri = $endpoint->uri();
        if ($this->prependPath !== null) {
            return $this->prependPath . $uri;
        }

        return $uri;
    }

    /** @return array<mixed> */
    protected function buildOptions(AbstractEndpoint $endpoint): array
    {
        $options  = $this->optionBuilder ? ($this->optionBuilder)($endpoint) : [];
        $params   = $endpoint->params();
        $body     = $endpoint->body();
        $formData = $endpoint->formData();

        if (! array_key_exists('query', $options) && count($params) > 0) {
            $options['query'] = $params;
        }

        if (! array_key_exists('json', $options) && is_array($body)) {
            $options['json'] = $body;
        }

        if (! array_key_exists('form_params', $options) && is_array($formData)) {
            $options['form_params'] = $formData;
        }

        return $options;
    }
}
