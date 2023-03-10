<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Client;

use ADS\OpenApi\Codegen\Endpoint\Endpoint;
use ADS\Util\ArrayUtil;
use Closure;
use EventEngine\Data\ImmutableRecord;

use function array_key_exists;
use function array_shift;
use function is_array;

abstract class Client
{
    private const ENDPOINT_PROPERTIES_OPTIONS_MAP = [
        'query' => 'query',
        'body' => 'json',
        'form' => 'form_params',
    ];

    /** @param array<string, mixed> $configs */
    public function __construct(
        private readonly ClientWrapper $client,
        private readonly array $configs = [],
        private readonly Closure|null $optionBuilder = null,
    ) {
    }

    protected function performRequest(Endpoint $endpoint): mixed
    {
        $method = $endpoint->method();
        $uri = $endpoint->uri();
        $options = $this->buildOptions($endpoint);

        $response = $this->client->request($method, $uri, $options);

        if (($this->configs['transformHal'] ?? false) && is_array($response)) {
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
    protected function buildOptions(Endpoint $endpoint): array
    {
        $options = $this->optionBuilder ? ($this->optionBuilder)($endpoint) : [];

        foreach (self::ENDPOINT_PROPERTIES_OPTIONS_MAP as $propertyName => $option) {
            $property = $endpoint->{$propertyName}();

            if (
                array_key_exists($option, $options)
                || ! ($property instanceof ImmutableRecord)
                || empty($property->toArray())
            ) {
                continue;
            }

            $data = $property->toArray();
            $data = ArrayUtil::rejectNullValues($data);
            $options[$option] = ArrayUtil::removeSuffixFromKeys($data, '[]');
        }

        return $options;
    }
}
