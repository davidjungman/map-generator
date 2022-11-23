<?php

namespace App\Service\CellAccessor;

use App\Dto\Cell;
use App\Dto\Map;
use App\Dto\Utils\MapSetting;

class LavaCellAccessor extends AbstractCellAccessor implements CellAccessorInterface
{
    protected function supports(Cell $cell): bool
    {
        // TODO: After mergin lava
        return false;
    }
}