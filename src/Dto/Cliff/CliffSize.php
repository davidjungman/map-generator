<?php

namespace App\Dto\Cliff;

class CliffSize
{
    public function __construct(
        public readonly int $maxCliffLevel = 3
    ) {
    }
}