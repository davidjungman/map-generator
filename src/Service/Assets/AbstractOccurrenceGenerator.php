<?php

namespace App\Service\Assets;

use App\Dto\Utils\MapSetting;
use App\Service\Calculator\ChanceCalculator;
use App\Service\Calculator\OccurrenceCalculator;
use App\Service\CellAccessor;

abstract class AbstractOccurrenceGenerator implements AssetGenerator
{
    public function __construct(
        protected readonly CellAccessor $cellAccessor,
        protected readonly OccurrenceCalculator $occurrenceCalculator,
        protected readonly ChanceCalculator $chanceCalculator,
        protected readonly array $occurrenceChance,
        protected readonly int $weight
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $occurrences = $this->occurrenceCalculator->calculate($this->occurrenceChance['min'], $this->occurrenceChance['max'], $mapSetting->totalCells());

        for($occurrence = 0; $occurrence < $occurrences; $occurrence++) {
            $this->createAttribute();
        }
    }

    public abstract function createAttribute(): void;

    public function getWeight(): int
    {
        return $this->weight;
    }
}