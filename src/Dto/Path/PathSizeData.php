<?php

namespace App\Dto\Path;

class PathSizeData
{
    public function __construct(
        public readonly int $min,
        public readonly int $max
    ) {
    }
}