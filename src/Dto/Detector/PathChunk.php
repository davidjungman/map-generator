<?php

namespace App\Dto\Detector;

use App\Dto\Cell;

class PathChunk
{
    /** @param Cell[] $cells */
    public function __construct(
        public readonly array $cells
    ) {
    }
}