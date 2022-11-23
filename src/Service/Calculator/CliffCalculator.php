<?php

namespace App\Service\Calculator;

use App\Dto\Cliff\Cliff;
use App\Dto\Cliff\CliffSize;
use App\Dto\Utils\Coordinates;
use App\Enum\BorderType;

class CliffCalculator
{
    public function calculate(
        CliffSize $cliffSize,
        Coordinates $start,
        Coordinates $end,
        BorderType $border
    ): Cliff {

    }
}