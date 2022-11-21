<?php

namespace App\Dto\Utils;

class Coordinates
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {
    }
}