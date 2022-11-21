<?php

namespace App\Service;

use App\Dto\Map;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;

class MapGenerator
{
    public function __construct(
        private readonly CellGenerator $cellGenerator,
        private readonly RowGenerator $rowGenerator
    ) {
    }

    public function generate(int $rowCount, int $columnCount): Map
    {
        $rowCount--;
        $columnCount--;
        $mapSetting = new MapSetting($rowCount, $columnCount);

        $rows = [];
        for ($rowIndex = 0; $rowIndex <= $rowCount; $rowIndex++) {
            $cells = [];
            for($columnIndex = 0; $columnIndex <= $columnCount; $columnIndex++) {
                $coordinates = new Coordinates($rowIndex, $columnIndex);
                $cells[] = $this->cellGenerator->generate($coordinates, $mapSetting);
            }
            $rows[] = $this->rowGenerator->generate($cells);
        }

        return new Map($rows);
    }
}