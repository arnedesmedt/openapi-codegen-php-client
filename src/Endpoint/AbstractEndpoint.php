<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic OpenAPI PHP code generator.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADS\OpenApi\Codegen\Endpoint;

use ADS\Util\ArrayUtil;
use ADS\ValueObjects\Util;
use ADS\ValueObjects\ValueObject;
use EventEngine\Data\ImmutableRecord;
use UnexpectedValueException;

use function array_diff;
use function array_keys;
use function array_map;
use function count;
use function implode;
use function ltrim;
use function rtrim;
use function sprintf;
use function str_ends_with;
use function str_replace;
use function strval;

/**
 * Abstract endpoint implementation.
 */
abstract class AbstractEndpoint implements Endpoint
{
    protected string $method;
    protected string $uri;
    /** @var array<string>  */
    protected array $pathParameterNames = [];
    /** @var array<string, string|int>  */
    protected array $pathParameters = [];
    /** @var array<string>  */
    protected array $queryParameterNames = [];
    /** @var array<string, string|int|array<int,mixed>> */
    protected array $queryParameters = [];
    /** @var array<string, mixed>|null  */
    protected array|null $body = null;
    /** @var array<string, mixed>|null  */
    protected array|null $formData = null;

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        $uri = $this->uri;

        foreach ($this->pathParameterNames as $pathParameterName) {
            $uri = str_replace(
                sprintf('{%s}', $pathParameterName),
                strval($this->pathParameters[$pathParameterName]),
                $uri,
            );
        }

        return ltrim($uri, '/');
    }

    /**
     * {@inheritdoc}
     */
    public function queryParameters(): array
    {
        return $this->queryParameters;
    }

    /** @return array<string> */
    public function queryParameterNames(): array
    {
        return array_map(
            static fn (string $param) => str_ends_with($param, '[]')
                ? rtrim($param, '[]')
                : $param,
            $this->queryParameterNames,
        );
    }

    public function setQueryParameters(ImmutableRecord|array|null $queryParameters): static
    {
        $this->queryParameters = $this->processParameters($queryParameters, $this->queryParameterNames()) ?? [];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function pathParameters(): array
    {
        return $this->pathParameters;
    }

    public function setPathParameters(ImmutableRecord|array|null $queryParameters): static
    {
        /** @var array<string, int|string> $pathParameters */
        $pathParameters = $this->processParameters($queryParameters, $this->pathParameterNames) ?? [];
        $this->pathParameters = $pathParameters;

        return $this;
    }

    public function body(): array|null
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody(ImmutableRecord|array|null $body)
    {
        $this->body = $this->processData($body);

        return $this;
    }

    public function formData(): array|null
    {
        return $this->formData;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormData(array|null $formData)
    {
        $this->formData = $this->processData($formData);

        return $this;
    }

    /**
     * @param array<string, mixed>|null $data
     *
     * @return array<string, mixed>|null
     */
    private function processData(ImmutableRecord|array|null $data): array|null
    {
        if ($data === null) {
            return $data;
        }

        /** @var array<string, mixed> $scalarData */
        $scalarData = Util::toScalar($data);
        $scalarData = ArrayUtil::rejectNullValues($scalarData);
        $scalarData = ArrayUtil::rejectEmptyArrayValues($scalarData);

        return $scalarData;
    }

    /**
     * @param ImmutableRecord|array<string, int|string|array<int,mixed>|ValueObject>|null $parameters
     * @param array<string>                                                               $allowedParameterNames
     *
     * @return array<string, int|string|array<int,mixed>>|null
     */
    private function processParameters(
        array|ImmutableRecord|null $parameters,
        array $allowedParameterNames,
    ): array|null {
        if ($parameters === null) {
            return null;
        }

        /** @var array<string, int|string|array<int,mixed>>  $scalarParameters */
        $scalarParameters = Util::toScalar($parameters);

        $this->checkAllParametersAllowed($scalarParameters, $allowedParameterNames);

        /** @var array<string, int|string|array<int,mixed>> $scalarParametersWithoutNull */
        $scalarParametersWithoutNull = ArrayUtil::rejectNullValues($scalarParameters);

        return $scalarParametersWithoutNull;
    }

    /**
     * @param array<string, mixed>|null $parameters
     * @param array<string, string>     $allowedParameterNames
     *
     * @throws UnexpectedValueException
     */
    private function checkAllParametersAllowed(array|null $parameters, array $allowedParameterNames): void
    {
        if ($parameters === null) {
            return;
        }

        $invalidParams = array_diff(array_keys($parameters), $allowedParameterNames);
        $countInvalid  = count($invalidParams);

        if ($countInvalid <= 0) {
            return;
        }

        $whitelist     = implode('", "', $allowedParameterNames);
        $invalidParams = implode('", "', $invalidParams);
        $message       = '"%s" is not a valid parameter. Allowed parameters are "%s".';
        if ($countInvalid > 1) {
            $message = '"%s" are not valid parameters. Allowed parameters are "%s".';
        }

        throw new UnexpectedValueException(
            sprintf($message, $invalidParams, $whitelist),
        );
    }
}
