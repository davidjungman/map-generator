<?php

namespace App\Service\Assets;

use App\Dto\Attribute\HotRockAttribute;
use App\Dto\Cell;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Service\Calculator\OccurrenceCalculator;
use App\Service\Calculator\PoolCalculator;
use App\Service\CellAccessor\CellAccessor;

class HotRockGenerator implements AssetGenerator
{
    public function __construct(
        private readonly int $weight,
        private readonly array $occurrenceChance,
        private readonly int $maxPoolSize,
        private readonly OccurrenceCalculator $occurrenceCalculator,
        private readonly CellAccessor $cellAccessor,
        private readonly PoolCalculator $poolCalculator
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $occurrences = $this->occurrenceCalculator->calculate($this->occurrenceChance['min'], $this->occurrenceChance['max'], $mapSetting->totalCells());

        $disabledCells = [];
        while($occurrences > 1) {
            $poolSize = random_int(2, $this->maxPoolSize);
            $usedCells = $this->generatePool($poolSize, $mapSetting, $disabledCells);
            $disabledCells = array_merge($disabledCells, $usedCells);
            $occurrences -= $poolSize;
        }
    }

    /** @param Cell[] $disabledCells */
    private function generatePool(int $poolSize, MapSetting $mapSetting, array $disabledCells): array
    {
        $rootCell = $this->cellAccessor->random();

        $disabledCells[] = $rootCell;
        $mapSettingCoordinates = new Coordinates($mapSetting->rowCount, $mapSetting->columnCount);

        $poolCells = $this->poolCalculator->calculate($rootCell, $mapSettingCoordinates, $poolSize, $disabledCells);
        foreach($poolCells as $cell) {
            $this->createAttribute($cell);
        }

        return $poolCells;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    private function createAttribute(Cell $cell): void
    {
        if(!$cell->isOccupied()) {
            $attribute = new HotRockAttribute();
            $cell->addAttribute($attribute);
            $cell->setOccupied();
        }
    }
}