<?php

namespace App\Service\Assets;

use App\Dto\Attribute\DeadBodyAttribute;
use App\Dto\Utils\MapSetting;
use App\Service\Calculator\ChanceCalculator;
use App\Service\Calculator\OccurrenceCalculator;
use App\Service\CellAccessor;

class DeadBodyGenerator implements AssetGenerator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor,
        private readonly OccurrenceCalculator $occurrenceCalculator,
        private readonly ChanceCalculator $chanceCalculator,
        private readonly array $chances,
        private readonly array $occurrenceChance,
        private readonly int $weight
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $occurrences = $this->occurrenceCalculator->calculate($this->occurrenceChance['min'], $this->occurrenceChance['max'], $mapSetting->totalCells());

        for($occurrence = 0; $occurrence < $occurrences; $occurrence++) {
            $this->createAttribute();
        }
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    private function createAttribute(): void
    {
        $cell = $this->cellAccessor->randomUnoccupied();
        $lootValue = $this->chanceCalculator->calculate($this->chances);
        $attribute = new DeadBodyAttribute($lootValue);

        $cell->valueAttribute = $attribute;
        $cell->addAttribute($attribute);
        $cell->setOccupied();
    }
}