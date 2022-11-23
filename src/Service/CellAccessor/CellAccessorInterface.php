<?php

namespace App\Service\CellAccessor;

use App\Dto\Map;
use App\Dto\Utils\MapSetting;

interface CellAccessorInterface
{
    public function build(Map $map, MapSetting $mapSetting):void;
}