<?php

namespace App\Service\Calculator;

use App\Dto\Cell;
use App\Dto\Utils\Coordinates;
use App\Service\CellAccessor;

class PoolCalculator
{
    /** @var array<int, array<int, Cell>> $usedCells */
    private array $usedCells = [];

    /** @var Cell[] */
    private array $poolCells;

    public function __construct(
        private readonly CellAccessor $cellAccessor
    ) {
    }

    /** @return Cell[] */
    public function calculate(
        Cell $rootCell,
        Coordinates $maxCoordinates,
        int $poolSize,
        array $disabledCells = []
    ): array {
        $this->initializeDisabledCells($disabledCells);
        $this->poolCells = [];
        $currentCell = $rootCell;
        for ($currentSize = 0; $currentSize <= $poolSize; $currentSize++) {
            $nextCell = $this->getNextCell(
                $currentCell,
                $maxCoordinates
            );
            if ($nextCell !== null){
                $currentCell = $nextCell;
            }
        }

        $this->poolCells[] = $rootCell;
        return $this->poolCells;
    }

    /** @param Cell[] $disabledCells */
    private function initializeDisabledCells(array $disabledCells): void
    {
        foreach($disabledCells as $disabledCell) {
            $this->usedCells[$disabledCell->x][$disabledCell->y] = $disabledCell;
        }
    }

    private function getNextCell(Cell $currentCell, Coordinates $maxCoordinates): ?Cell
    {
        $coordinates = $this->getOptimalChange($currentCell);

        $cell = $this->cellAccessor->get($coordinates->x, $coordinates->y);
        if (isset($this->usedCells[$cell->x][$cell->y])) {
            return null;
        }

        $this->usedCells[$cell->x][$cell->y] = $cell;
        $this->poolCells[] = $cell;

        return $cell;
    }

    /**
     * @param int[] $xPossibleChanges
     * @param int[] $yPossibleChanges
     */
    private function getOptimalChange(
        Cell $currentCell,
    ): Cell {
        $topNeighbor = $this->cellAccessor->getTopNeighbor($currentCell);
        $topNeighborInPool = false;
        if (
            $topNeighbor !== null
            && isset($this->usedCells[$topNeighbor->x][$topNeighbor->y])
        ) {
            $topNeighborInPool = true;
        }

        $bottomNeighbor = $this->cellAccessor->getBottomNeighbor($currentCell);
        $bottomNeighborInPool = false;
        if (
            $bottomNeighbor !== null
            && isset($this->usedCells[$bottomNeighbor->x][$bottomNeighbor->y])
        ) {
            $bottomNeighborInPool = true;
        }

        $leftNeighbor = $this->cellAccessor->getLeftNeighbor($currentCell);
        $leftNeighborInPool = false;
        if (
            $leftNeighbor !== null
            && isset($this->usedCells[$leftNeighbor->x][$leftNeighbor->y])
        ) {
            $leftNeighborInPool = true;
        }

        $rightNeighbor = $this->cellAccessor->getRightNeighbor($currentCell);

        if (
            $topNeighbor !== null
            && $topNeighborInPool === false
            && $bottomNeighborInPool === false
            && $leftNeighborInPool === false
        ) {
            return $topNeighbor;
        }
        if (
            $rightNeighbor !== null
            && $bottomNeighborInPool === true
        ) {
            return $rightNeighbor;
        }
        if (
            $bottomNeighbor !== null
            && $leftNeighborInPool === true
        ) {
            return $bottomNeighbor;
        }
        if (
            $leftNeighbor !== null
            && $topNeighborInPool === true
        ) {
            return $leftNeighbor;
        }

        if (
            $leftNeighbor !== null
        ) {
            return $leftNeighbor;
        }
        if ($rightNeighbor !== null){
            return $rightNeighbor;
        }
    }

    private function getPossibleChange(int $coordinate, int $maxCoordinate): array
    {
        $options = [];
        if ($coordinate !== 0) {
            $options[] = -1;
        }
        if ($coordinate !== $maxCoordinate) {
            $options[] = 1;
        }

        $options[] = 0;

        return $options;
    }
}