<?php

namespace App\Dto\Attribute;

use App\Enum\AttributeType;

class DeadBodyAttribute extends Attribute implements AttributeWithValue
{
    public function __construct(
        public readonly int $lootValue
    ) {
        parent::__construct(AttributeType::DEAD_BODY);
    }

    public function renderCss(): string
    {
        return "background: green;";
    }

    public function renderValue(): string
    {
        return (string) $this->lootValue;
    }
}