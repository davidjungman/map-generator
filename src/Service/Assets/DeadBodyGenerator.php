<?php

namespace App\Service\Assets;

use App\Dto\Attribute\DeadBodyAttribute;
use App\Service\Calculator\ChanceCalculator;
use App\Service\Calculator\OccurrenceCalculator;
use App\Service\CellAccessor\CellAccessor;

class DeadBodyGenerator extends AbstractOccurrenceGenerator implements AssetGenerator
{
    /** @param float[] $chances */
    public function __construct(
        protected readonly CellAccessor $cellAccessor,
        protected readonly OccurrenceCalculator $occurrenceCalculator,
        protected readonly ChanceCalculator $chanceCalculator,
        protected readonly array $occurrenceChance,
        protected readonly int $weight,
        protected readonly array $chances,
    ) {
    }

    public function createAttribute(): void
    {
        $cell = $this->cellAccessor->randomUnoccupied();
        $lootValue = $this->chanceCalculator->calculate($this->chances);
        $attribute = new DeadBodyAttribute($lootValue);

        $cell->valueAttribute = $attribute;
        $cell->addAttribute($attribute);
        $cell->setOccupied();
    }
}