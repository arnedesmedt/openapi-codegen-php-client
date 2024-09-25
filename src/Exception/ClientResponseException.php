<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Exception;

use Exception;

use function sprintf;

// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class ClientResponseException extends Exception
{
    public static function fromStatusCodeAndMessage(int $statusCode, string $message): self
    {
        return new self(
            sprintf(
                'The client responded with status code %d and message: %s',
                $statusCode,
                $message,
            ),
            $statusCode,
        );
    }
}
