<?php

namespace App\Service\Calculator;

use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Cell;
use App\Dto\Cliff\CliffData;
use App\Dto\Detector\PathChunk;
use App\Enum\BorderType;
use App\Service\CellAccessor\CellAccessor;

class CliffCalculator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor
    ) {
    }

    public function calculate(
        PathChunk $pathChunk,
        BorderType $direction
    ): CliffData {
        $cells = $pathChunk->cells;
        $firstCell = $cells[1];
        $firstCliffCell = $this->calculateCliffCell($firstCell, $direction);

        $totalCells = \count($cells);
        $lastCell = $cells[$totalCells-2];
        $lastCliffCell = $this->calculateCliffCell($lastCell, $direction);

        return new CliffData(
            parentStartCell: $firstCell,
            cliffStartCell: $firstCliffCell,
            parentEndCell: $lastCell,
            cliffEndCell: $lastCliffCell
        );
    }

    /** @return Cell[] */
    public function calculateIncreasedElevation(
        PathChunk $pathChunk,
    ): array {
        $cells = $pathChunk->cells;

        $cellsToElevate = [];
        foreach($cells as $cell) {
            if (!$this->canBeElevated($cell)) {
                continue;
            }
            $cellsToElevate[] = $cell;
        }

        return $cellsToElevate;
    }

    private function calculateCliffCell(Cell $firstCell, BorderType $border): Cell
    {
        switch($border) {
            case BorderType::LEFT:
                return $this->cellAccessor->getRightNeighbor($firstCell);
            case BorderType::RIGHT:
                return $this->cellAccessor->getLeftNeighbor($firstCell);
            case BorderType::TOP:
                return $this->cellAccessor->getBottomNeighbor($firstCell);
            case BorderType::BOTTOM:
                return $this->cellAccessor->getTopNeighbor($firstCell);
        }

        throw new \Exception("invalid borderType");
    }

    private function canBeElevated(Cell $cell): bool
    {
        return $this->hasElevationLowerOrSameOrNullInAllDirections($cell);
    }

    private function hasElevationLowerOrSameOrNullInAllDirections(Cell $cell, int $currentCellElevation = 1): bool
    {
        $neighbors = $this->cellAccessor->getDirectNeighbors($cell);
        $counter = 0;

        foreach($neighbors as $neighbor) {
            if ($neighbor === null) {
                $counter++;
                continue;
            }

            foreach($neighbor->attributes as $attribute) {
                if ($attribute instanceof ElevationAttribute) {
                    if ($attribute->elevation <= $currentCellElevation+1) {
                        $counter++;
                    }
                }
            }
        }

        if ($counter === 4) {
            return true;
        }

        return false;
    }
}