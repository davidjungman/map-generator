<?php

namespace App\Service\Assets;

use App\Dto\Utils\MapSetting;

interface AssetGenerator
{
    public function generate(MapSetting $mapSetting): void;

    public function getWeight(): int;
}