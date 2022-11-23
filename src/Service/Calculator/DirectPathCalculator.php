<?php

namespace App\Service\Calculator;

use App\Dto\Cell;
use App\Dto\Path\DirectPath;
use App\Enum\Coordinate;
use App\Service\CellAccessor\CellAccessor;

class DirectPathCalculator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor
    ) {
    }

    public function calculate(Cell $startCell, Cell $endCell): DirectPath
    {
        $coordinate = $this->getSharedCoordinate($startCell, $endCell);

        $xCoordinates = [];
        $yCoordinates = [];
        if (Coordinate::X === $coordinate) {
            $yCoordinates = $this->calculatePath($startCell->y, $endCell->y);
            $xCoordinates = [$startCell->x];
        } else {
            $xCoordinates = $this->calculatePath($startCell->x, $endCell->x);
            $yCoordinates = [$startCell->y];
        }

        $cells =  $this->getCells($xCoordinates, $yCoordinates);
        return new DirectPath($cells, $coordinate);
    }

    /**
     * One will always be same - that means you will always generate straight line
     * @return Cell[]
     */
    public function getCells(array $x, array $y): array
    {
        $cells = [];

        foreach($x as $row) {
            foreach($y as $column) {
                $cells[] = $this->cellAccessor->get($row, $column);
            }
        }

        return $cells;
    }

    /** @return int[] */
    private function calculatePath(int $start, int $end): array
    {
        $result = [];
        if ($start < $end) {
            for($i = $start; $i <= $end; $i++) {
                $result[] = $i;
            }
        }  else if ($start > $end) {
            $result[] = $end;
            for($i = $end; $i <= $start; $i++) {
                $result[] = $i;
            }
        } else {
            $result[] = $start;
            $result[] = $end;
        }

        return $result;
    }

    private function getSharedCoordinate(Cell $startCell, Cell $endCell): Coordinate
    {
        if ($startCell->x === $endCell->x) {
            return Coordinate::X;
        }

        if ($endCell->y === $endCell->y) {
            return Coordinate::Y;
        }

        throw new \Exception('For DirectPath Cells must be in direct line.');
    }
}