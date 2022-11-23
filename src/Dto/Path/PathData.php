<?php

namespace App\Dto\Path;

use App\Dto\Cell;
use App\Dto\Utils\Coordinates;

class PathData
{
    public function __construct(
        public readonly Coordinates $start,
        public readonly Coordinates $end
    ) {
    }

    public static function fromCells(Cell $start, Cell $end): self
    {
        return new PathData($start->toCoordinates(), $end->toCoordinates());
    }
}