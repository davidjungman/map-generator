<?php

namespace App\Service\Calculator;

use App\Dto\Cell;
use App\Dto\Cliff\CliffData;
use App\Dto\Detector\PathChunk;
use App\Enum\BorderType;
use App\Service\CellAccessor;

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
}