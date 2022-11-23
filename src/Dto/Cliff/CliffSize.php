<?php

namespace App\Dto\Cliff;

class CliffSize
{
    public function __construct(
        public readonly int $start,
        public readonly int $end
    ) {
    }
}