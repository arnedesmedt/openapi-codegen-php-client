<?php

declare(strict_types=1);

namespace Client;

interface ClientWrapper
{
    /**
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function client();

    /**
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    public function request(string $method, string $uri, array $options = []): array;
}
