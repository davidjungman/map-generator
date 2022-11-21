<?php

namespace App\Service;

use App\Dto\Cell;
use App\Dto\Map;
use App\Dto\Row;

class MapGenerator
{
    public function __construct(
        public readonly CellGenerator $cellGenerator
    ) {
    }

    public function generate(int $rowCount, int $columnCount): Map
    {
        $rows = [];
        for ($rowIndex = 0; $rowIndex <= $rowCount; $rowIndex++) {
            $cells = [];
            for($columnIndex = 0; $columnIndex <= $columnCount; $columnIndex++) {
                $cells[] = $this->cellGenerator->generate($rowIndex, $columnIndex);
            }
            $rows[] = new Row($cells);
        }

        return new Map($rows);
    }
}