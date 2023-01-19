<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Exception;

use Throwable;

abstract class ExceptionHandler
{
    public static function fromClientException(
        Throwable $exception,
        callable|null $exceptionHandler = null,
    ): Throwable {
        if ($exceptionHandler === null) {
            return $exception;
        }

        $content = static::contentFromException($exception);

        $throwable = $exceptionHandler($content);

        if (! $throwable instanceof Throwable) {
            return $exception;
        }

        return $throwable;
    }

    /** @return array<mixed> */
    abstract protected static function contentFromException(Throwable $exception): array;
}
