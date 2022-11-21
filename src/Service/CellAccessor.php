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
}