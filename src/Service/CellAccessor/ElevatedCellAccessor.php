<?php

namespace App\Service\CellAccessor;

use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Cell;
use App\Dto\Map;
use App\Dto\Utils\MapSetting;

class ElevatedCellAccessor extends AbstractCellAccessor implements CellAccessorInterface
{
    protected function supports(Cell $cell): bool
    {
        return $cell->hasAttribute(ElevationAttribute::class);
    }
}