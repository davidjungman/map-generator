<?php

namespace App\Service;

use App\Dto\Cell;

class CellGenerator
{
    public function generate(int $x, int $y): Cell
    {
        return new Cell($x, $y, false);
    }
}