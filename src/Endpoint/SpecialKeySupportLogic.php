<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Endpoint;

trait SpecialKeySupportLogic
{
    /** @param array<string, string> $map */
    private function convertByMap(string $key, array $map): string
    {
        return $map[$key] ?? $key;
    }
}
