<?php

namespace App\Dto;

use App\Dto\Attribute\Attribute;
use App\Dto\Attribute\AttributeWithValue;
use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Utils\Coordinates;
use App\Enum\BorderType;

class Cell
{
    /**
     * @param BorderType[] $borderTypes
     * @param Attribute[] $attributes
     */
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isBorderCell,
        public readonly ?array $borderTypes = null,
        public array $attributes = [],
        public ?AttributeWithValue $valueAttribute = null,
        public bool $occupied = false,
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

    public function toCoordinates(): Coordinates
    {
        return new Coordinates($this->x, $this->y);
    }
}