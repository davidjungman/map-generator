<?php

namespace App\Service;

use App\Dto\Cell;
use App\Dto\Map;
use App\Dto\Utils\MapSetting;

class CellAccessor
{
    /** @var array<int, array<int, Cell> $cells */
    private array $cells;

    private MapSetting $mapSetting;

    public function build(Map $map, MapSetting $mapSetting): void
    {
        foreach($map->rows as $row) {
            foreach($row->cells as $cell) {
                $this->cells[$cell->x][$cell->y] = $cell;
            }
        }
        $this->mapSetting = $mapSetting;
    }

    public function random(): Cell
    {
        $x = random_int(0, $this->mapSetting->rowCount);
        $y = random_int(0, $this->mapSetting->columnCount);

        return $this->cells[$x][$y];
    }

    // terrible solution
    public function randomUnoccupied(): Cell
    {
        $x = random_int(0, $this->mapSetting->rowCount);
        $y = random_int(0, $this->mapSetting->columnCount);

        /** @var Cell $cell */
        $cell = $this->cells[$x][$y];

        if ($cell->isOccupied() === true) {
            return $this->randomUnoccupied();
        }

        return $cell;
    }

    public function randomUnoccupiedExceptBorderCells(): Cell
    {
        $x = random_int(1, $this->mapSetting->rowCount-1);
        $y = random_int(1, $this->mapSetting->columnCount-1);

        /** @var Cell $cell */
        $cell = $this->cells[$x][$y];

        if ($cell->isOccupied() === true) {
            return $this->randomUnoccupiedExceptBorderCells();
        }

        return $cell;
    }

    public function get(int $x, int $y): Cell
    {
        if ($x > $this->mapSetting->rowCount) {
            throw new \Exception("Invalid X Coordinate. $x");
        }
        if ($y > $this->mapSetting->columnCount) {
            throw new \Exception("Invalid Y Coordinate. $y");
        }

        return $this->cells[$x][$y];
    }

    /** @return Cell[] */
    public function getDirectNeighbors(Cell $cell): array
    {
        $neighbors = [];

        $neighbors[] = $this->getLeftNeighbor($cell);
        $neighbors[] = $this->getRightNeighbor($cell);
        $neighbors[] = $this->getTopNeighbor($cell);
        $neighbors[] = $this->getBottomNeighbor($cell);

        return $neighbors;
    }

    public function getLeftNeighbor(Cell $cell): ?Cell
    {
        $leftNeighborY = $cell->y - 1;

        if ($leftNeighborY < 0) {
            return null;
        }

        return $this->get($cell->x, $leftNeighborY);
    }

    public function getRightNeighbor(Cell $cell): ?Cell
    {
        $rightNeighborY = $cell->y +1;

        if ($rightNeighborY > $this->mapSetting->columnCount) {
            return null;
        }

        return $this->get($cell->x, $rightNeighborY);
    }

    public function getBottomNeighbor(Cell $cell): ?Cell
    {
        $topNeighborX = $cell->x + 1;

        if ($topNeighborX > $this->mapSetting->rowCount) {
            return null;
        }

        return $this->get($topNeighborX, $cell->y);
    }

    public function getTopNeighbor(Cell $cell): ?Cell{
        $bottomNeighborX = $cell->x -1;

        if ($bottomNeighborX < 0) {
            return null;
        }

        return $this->get($bottomNeighborX, $cell->y);
    }
}