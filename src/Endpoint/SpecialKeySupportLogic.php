<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Endpoint;

use function array_key_exists;
use function rtrim;
use function str_ends_with;

trait SpecialKeySupportLogic
{
    /** @param array<string, string> $map */
    private function convertByMap(string $key, array $map): string
    {
        return $this->removeArrayBrackets(
            array_key_exists($key, $map)
                ? $map[$key]
                : $key,
        );
    }

    private function removeArrayBrackets(string $key): string
    {
        if (str_ends_with($key, '[]')) {
            return rtrim($key, '[]');
        }

        return $key;
    }
}
