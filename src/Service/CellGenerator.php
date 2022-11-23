<?php

namespace App\Service;

use App\Dto\Cell;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Enum\BorderType;

class CellGenerator
{
    public function generate(
        Coordinates $coordinates,
        MapSetting $mapSetting
    ): Cell {
        $isBorderCell = $this->isBorderCell($coordinates, $mapSetting);
        $borderTypes = null;
        if ($isBorderCell === true) {
            $borderTypes = $this->detectBorderType($coordinates, $mapSetting);
        }
        return new Cell($coordinates->x, $coordinates->y, $isBorderCell, $borderTypes);
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

    /** @return BorderType[] */
    private function detectBorderType(Coordinates $coordinates, MapSetting $mapSetting): array
    {
        $borderType = [];
        if ($coordinates->x === 0) {
            $borderType[] = BorderType::TOP;
        }
        if ($coordinates->x === $mapSetting->rowCount) {
            $borderType[] = BorderType::BOTTOM;
        }
        if ($coordinates->y === 0) {
            $borderType[] = BorderType::LEFT;
        }
        if ($coordinates->y === $mapSetting->columnCount) {
            $borderType[] = BorderType::RIGHT;
        }

        return $borderType;
    }
}