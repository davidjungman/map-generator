<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

class ElevationAttribute extends Attribute
{
    public function __construct(
        public readonly int $elevation
    ) {
        parent::__construct(AttributeType::ELEVATION);
    }
}