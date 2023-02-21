<?php

declare(strict_types=1);

namespace ADS\OpenApi\Codegen\Endpoint;

use EventEngine\Data\ImmutableRecord;

interface Endpoint extends ImmutableRecord
{
    public function method(): string;

    public function uri(): string;

    public function query(): ImmutableRecord|null;

    public function body(): ImmutableRecord|null;

    public function form(): ImmutableRecord|null;
}
