<?php

namespace App\Service;

use App\Dto\Cell;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;

class CellGenerator
{
    public function generate(
        Coordinates $coordinates,
        MapSetting $mapSetting
    ): Cell {
        $isBorderCell = $this->isBorderCell($coordinates, $mapSetting);
        return new Cell($coordinates->x, $coordinates->y, $isBorderCell);
    }

    private function isBorderCell(Coordinates $coordinates, MapSetting $mapSetting): bool
    {
        if (
            $coordinates->x === 0
            || $coordinates->y === 0
        ) {
            return true;
        }

        if (
            $coordinates->x === $mapSetting->rowCount
            || $coordinates->y === $mapSetting->columnCount
        ) {
            return true;
        }

        return false;
    }
}