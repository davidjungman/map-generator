<?php

namespace App\Dto\Path;

use App\Dto\Cell;
use App\Enum\Coordinate;

class DirectPath implements Path
{
    /** @param Cell[] $cells */
    public function __construct(
        public readonly array $cells,
        public readonly Coordinate $direction
    ) {
    }

    /** @return Cell[] */
    public function getCells(): array
    {
        return $this->cells;
    }

    public function count(): int
    {
        return \count($this->cells);
    }
}