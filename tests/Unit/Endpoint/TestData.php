<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Endpoint;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class TestData implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $test;
    private string|null $testNull = null;
}
