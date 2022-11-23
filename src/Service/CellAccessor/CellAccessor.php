<?php

namespace App\Service\CellAccessor;

use App\Dto\Cell;

class CellAccessor extends AbstractCellAccessor implements CellAccessorInterface
{
    protected function supports(Cell $cell): bool
    {
        return true;
    }
}