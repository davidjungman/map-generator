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
        private readonly array $occurrenceChance
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $occurrences = $this->occurrenceCalculator->calculate($this->occurrenceChance['min'], $this->occurrenceChance['max'], $mapSetting->totalCells());

        for($occurrence = 0; $occurrence < $occurrences; $occurrence++) {
            $this->createAttribute();
        }
    }

    private function createAttribute(): void
    {
        $cell = $this->cellAccessor->random();
        $lootValue = $this->chanceCalculator->calculate($this->chances);
        $attribute = new DeadBodyAttribute($lootValue);

        $cell->addAttribute($attribute);
    }
}