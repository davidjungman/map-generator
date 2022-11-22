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

    public function getValue(): string
    {
        return (string) $this->lootValue;
    }
}