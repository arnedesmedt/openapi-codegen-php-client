<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Endpoint;

use EventEngine\Data\ImmutableRecord;
use EventEngine\Data\ImmutableRecordLogic;

use function array_diff_key;
use function array_flip;
use function ltrim;
use function sprintf;
use function str_replace;
use function strval;

// todo handle array query parameters with [] suffix

/**
 * @property string $method
 * @property string $uri
 */
trait EndpointLogic
{
    use ImmutableRecordLogic;

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        $uri = $this->uri;
        // todo if we switch to php8.2 make this a constant. (constants are not allowed in traits for php8.1)
        $diffPathParameterNames = [
            'method',
            'uri',
            'query',
            'body',
            'form',
        ];

        $data = $this->toArray();
        $pathParameters = array_diff_key($data, array_flip($diffPathParameterNames));
        foreach ($pathParameters as $pathParameterName => $pathParameter) {
            $uri = str_replace(
                sprintf('{%s}', $pathParameterName),
                strval($pathParameter),
                (string) $uri,
            );
        }

        return ltrim((string) $uri, '/');
    }

    public function query(): ImmutableRecord|null
    {
        return $this->query ?? null;
    }

    public function body(): ImmutableRecord|null
    {
        return $this->body ?? null;
    }

    public function form(): ImmutableRecord|null
    {
        return $this->form ?? null;
    }
}
