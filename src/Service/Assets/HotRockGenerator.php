<?php

namespace App\Service\Assets;

use App\Dto\Utils\MapSetting;

class HotRockGenerator implements AssetGenerator
{
    public function generate(MapSetting $mapSetting): void
    {
    }

    public function getWeight(): int
    {
        return 1;
    }
}