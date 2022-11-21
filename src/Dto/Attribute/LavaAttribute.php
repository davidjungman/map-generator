<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

class LavaAttribute extends Attribute
{

    public function __construct(
    ) {
        parent::__construct(AttributeType::LAVA);
    }

    public function renderCss(): string
    {
        return "background: red;";
    }
}