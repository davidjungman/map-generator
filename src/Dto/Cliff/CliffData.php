<?php

namespace App\Dto\Cliff;

use App\Dto\Cell;

class CliffData
{
    public function __construct(
        public readonly Cell $parentStartCell,
        public readonly Cell $cliffStartCell,
        public readonly Cell $parentEndCell,
        public readonly Cell $cliffEndCell
    ) {
    }
}