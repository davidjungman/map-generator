<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

class HotRockAttribute extends Attribute
{
    public function __construct(
    ) {
        parent::__construct(AttributeType::HOT_ROCK);
    }
}