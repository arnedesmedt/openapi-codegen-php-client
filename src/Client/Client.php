<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use ADS\OpenApi\Codegen\Endpoint\AbstractEndpoint;
use ADS\OpenApi\Codegen\Endpoint\Builder;
use ADS\Util\ArrayUtil;
use Closure;

use function array_key_exists;
use function array_shift;
use function count;
use function is_array;

abstract class Client
{
    /** @param array<string, mixed> $configs */
    public function __construct(
        private readonly ClientWrapper $client,
        private readonly Builder $endpointBuilder,
        private readonly array $configs = [],
        private readonly Closure|null $optionBuilder = null,
    ) {
    }

    protected function endpoint(string $name): AbstractEndpoint
    {
        return ($this->endpointBuilder)($name);
    }

    protected function performRequest(AbstractEndpoint $endpoint): mixed
    {
        $method  = $endpoint->method();
        $uri     = $endpoint->uri();
        $options = $this->buildOptions($endpoint);

        $response = $this->client->request($method, $uri, $options);

        if ($this->configs['transformHal'] ?? false) {
            return $this->transformHal($response);
        }

        return $response;
    }

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

    /** @return array<mixed> */
    protected function buildOptions(AbstractEndpoint $endpoint): array
    {
        $options  = $this->optionBuilder ? ($this->optionBuilder)($endpoint) : [];
        $params   = $endpoint->queryParameters();
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
