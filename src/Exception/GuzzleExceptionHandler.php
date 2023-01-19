<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Exception;

use GuzzleHttp\Exception\ClientException;
use Throwable;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

final class GuzzleExceptionHandler extends ExceptionHandler
{
    /** @inheritDoc */
    protected static function contentFromException(Throwable $exception): array
    {
        assert($exception instanceof ClientException);

        $data = $exception->getResponse()->getBody()->getContents();

        /** @var array<mixed> $content */
        $content = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return $content;
    }
}
