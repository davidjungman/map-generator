<?php

namespace App\Service;

use App\Dto\Cell;
use App\Dto\Row;

class RowGenerator
{
    /** @param Cell[] $cells */
    public function generate(array $cells): Row
    {
        return new Row($cells);
    }
}