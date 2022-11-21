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

        if ($cell->occupied === true) {
            return $this->randomUnoccupied();
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
        $leftNeighborX = $cell->x - 1;

        if ($leftNeighborX < 0) {
            return null;
        }

        return $this->get($leftNeighborX, $cell->y);
    }

    public function getRightNeighbor(Cell $cell): ?Cell
    {
        $rightNeighborX = $cell->x +1;

        if ($rightNeighborX > $this->mapSetting->rowCount) {
            return null;
        }

        return $this->get($rightNeighborX, $cell->y);
    }

    public function getBottomNeighbor(Cell $cell): ?Cell
    {
        $topNeighborY = $cell->y + 1;

        if ($topNeighborY > $this->mapSetting->columnCount) {
            return null;
        }

        return $this->get($cell->x, $topNeighborY);
    }

    public function getTopNeighbor(Cell $cell): ?Cell{
        $bottomNeighborY = $cell->y -1;

        if ($bottomNeighborY < 0) {
            return null;
        }

        return $this->get($cell->x, $bottomNeighborY);
    }
}