<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic OpenAPI PHP code generator.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ADS\OpenApi\Codegen\Endpoint;

use ADS\ValueObjects\ValueObject;

/**
 * API endpoint interface.
 */
interface Endpoint
{
    /**
     * HTTP method for the current endpoint.
     */
    public function method(): string;

    /**
     * URI for the current endpoint.
     */
    public function uri(): string;

    /**
     * Params data for the current endpoint.
     *
     * @return array<string>
     */
    public function params(): array;

    /**
     * Body content for the current endpoint.
     *
     * @return array<string, mixed>
     */
    public function body(): array|null;

    /**
     * FormData content for the current endpoint.
     *
     * @return array<string, mixed>
     */
    public function formData(): array|null;

    /**
     * Set body data for the endpoint.
     *
     * @param array<string, mixed> $body
     *
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setBody(array|null $body);

    /**
     * Set body data for the endpoint.
     *
     * @param array<string, mixed> $formData
     *
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setFormData(array|null $formData);

    /**
     * Set params data for the endpoint.
     *
     * @param array<string, string|ValueObject>|null $params
     *
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setParams(array|null $params);
}
