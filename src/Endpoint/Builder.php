<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic OpenAPI PHP code generator.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADS\OpenApi\Codegen\Endpoint;

use function sprintf;

/**
 * Endpoint builder implementation.
 */
class Builder
{
    public function __construct(private readonly string $namespace)
    {
    }

    /**
     * Create an endpoint from name.
     */
    public function __invoke(string $endpointName): AbstractEndpoint
    {
        $className = sprintf('%s\\%s', $this->namespace, $endpointName);

        /** @var AbstractEndpoint $endpoint */
        $endpoint = new $className();

        return $endpoint;
    }
}
