<?php

namespace App\Dto\Utils;

class MapSetting
{
    public function __construct(
        public readonly int $rowCount,
        public readonly int $columnCount,
    ) {
    }

    public function totalCells(): int
    {
        return $this->rowCount * $this->columnCount;
    }
}