<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

abstract class Attribute
{
    public function __construct(
        public readonly AttributeType $attributeType
    ) {
    }

    abstract public function renderCss(): string;
}