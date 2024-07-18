<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Tests\Unit\Endpoint;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use ADS\OpenApi\Codegen\Endpoint\SpecialKeySupportLogic;
use EventEngine\Data\SpecialKeySupport;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class TestQuery implements JsonSchemaAwareRecord, SpecialKeySupport
{
    use JsonSchemaAwareRecordLogic;
    use SpecialKeySupportLogic;

    private string|null $testQuery = null;
    /** @var array<string>|null */
    private array|null $arrayQuery = null;

    /** @return array<string, string> */
    private static function arrayPropItemTypeMap(): array
    {
        return ['arrayQuery' => 'string'];
    }

    /** @return array<string,string> */
    public function keyMapping(): array
    {
        return [
            'testQuery' => 'testQuery',
            'arrayQuery[]' => 'arrayQuery',
        ];
    }
}
