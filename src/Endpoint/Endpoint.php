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
use EventEngine\Data\ImmutableRecord;

/**
 * API endpoint interface.
 */
interface Endpoint
{
    public function method(): string;

    public function uri(): string;

    /** @return array<int|string|array<int,mixed>> */
    public function queryParameters(): array;

    /** @return array<int|string> */
    public function pathParameters(): array;

    /** @return array<string, mixed> */
    public function body(): array|null;

    /** @return array<string, mixed> */
    public function formData(): array|null;

    /**
     * @param array<string, mixed> $body
     *
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setBody(array|null $body);

    /**
     * @param array<string, mixed> $formData
     *
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setFormData(array|null $formData);

    /** @param ImmutableRecord|array<string, int|string|array<int,mixed>|ValueObject>|null $params */
    public function setQueryParameters(ImmutableRecord|array|null $params): static;

    /** @param ImmutableRecord|array<string, int|string|ValueObject>|null $params */
    public function setPathParameters(ImmutableRecord|array|null $params): static;
}
