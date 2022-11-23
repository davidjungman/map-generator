<?php

namespace App\Helper;

use App\Dto\Cell;

class CellSorter
{
    public static function sortCellsByXCoordinate(): callable
    {
        return function(Cell $cellA, Cell $cellB) {
            if ($cellA->x === $cellB->x) {
                return 0;
            }

            if ($cellA->x > $cellB->x) {
                return 1;
            }

            return -1;
        };
    }

    public static function sortCellsByYCoordinate(): callable
    {
        return function (Cell $cellA, Cell $cellB) {
            if ($cellA->y === $cellB->y) {
                return 0;
            }

            if ($cellA->y > $cellB->y) {
                return 1;
            }

            return -1;
        };
    }
}