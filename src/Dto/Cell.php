<?php

namespace App\Dto;

use App\Dto\Attribute\Attribute;
use App\Dto\Attribute\AttributeWithValue;
use App\Enum\BorderCellType;

class Cell
{
    /**
     * @param BorderCellType[] $borderTypes
     * @param Attribute[] $attributes
     */
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isBorderCell,
        public readonly ?array $borderTypes = null,
        public array $attributes = [],
        public ?AttributeWithValue $valueAttribute = null,
        public bool $occupied = false
    ) {
    }

    public function addAttribute(Attribute $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    public function hasAttribute(string $attribute): bool
    {
        foreach($this->attributes as $existingAttribute) {
            if ($existingAttribute instanceof $attribute) {
                return true;
            }
        }
        return false;
    }

    public function setOccupied(): void
    {
        $this->occupied = true;
    }
}