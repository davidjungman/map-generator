<?php

namespace App\Dto\Cliff;

use App\Dto\Cell;

class Cliff
{
    /** @param Cell[] $cells */
    public function __construct(
        public readonly array $cells
    ) {
    }
}