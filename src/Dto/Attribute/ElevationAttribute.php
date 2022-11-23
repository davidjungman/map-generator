<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

class ElevationAttribute extends Attribute
{
    public function __construct(
        public int $elevation
    ) {
        parent::__construct(AttributeType::ELEVATION);
    }

    public function increaseElevation(int $elevation): void
    {
        $this->elevation = $elevation;
    }
}