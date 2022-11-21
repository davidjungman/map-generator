<?php

namespace App\Service\Assets;

use App\Dto\Attribute\LavaAttribute;
use App\Dto\Cell;
use App\Dto\Utils\MapSetting;
use App\Service\Calculator\OccurrenceCalculator;
use App\Service\CellAccessor;

class LavaGenerator implements AssetGenerator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor,
        private readonly OccurrenceCalculator $occurrenceCalculator,
        private readonly array $occurrenceChance,
        private readonly array $pullSize,
        private readonly int $weight,
    )
    {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $occurrences = $this->occurrenceCalculator->calculate($this->occurrenceChance['min'], $this->occurrenceChance['max'], $mapSetting->totalCells());

        for($occurrence = 0; $occurrence < $occurrences; $occurrence++) {
            $this->createLavaPulls($mapSetting);
        }
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    private function createAttribute(Cell $cell): void
    {

        $attribute = new LavaAttribute();
        $cellAttributes = $cell->getAttribute();
        if (in_array($attribute, $cellAttributes) === false){
            $cell->addAttribute($attribute);
        }
        $cell->setOccupied();

    }

    private function createLavaPulls(MapSetting $mapSetting):void
    {
        $cell = $this->cellAccessor->random();
        $this->createAttribute($cell);
        $pullSize = random_int($this->pullSize["min"],$this->pullSize["max"]);
        for ($size = 0;$size !== $pullSize;$size++)
        {
            $newCell = $cell;
            $direction = random_int(1,4);
            switch ($direction) {
                case 1:
                    if ($cell->x+1 < $mapSetting->rowCount){
                        $newCell = $this->cellAccessor->getCell($cell->x+1,$cell->y);
                        $this->createAttribute($newCell);
                    }
                    break;
                case 2:
                    if ($cell->x-1 > 0){
                        $newCell = $this->cellAccessor->getCell($cell->x-1,$cell->y);
                        $this->createAttribute($newCell);
                    }
                    break;
                case 3:
                    if ($cell->y+1 < $mapSetting->columnCount){
                        $newCell = $this->cellAccessor->getCell($cell->x,$cell->y+1);
                        $this->createAttribute($newCell);
                    }
                    break;
                case 4:
                    if ($cell->y-1 > 0){
                    $newCell = $this->cellAccessor->getCell($cell->x,$cell->y-1);
                    $this->createAttribute($newCell);
                    }
                    break;
            }
            $cell = $newCell;
        }
    }
}