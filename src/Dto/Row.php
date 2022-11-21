<?php

namespace App\Dto;

class Row
{
    /** @param Cell[] $cells */
    public function __construct(
        public readonly array $cells,
    ) {
    }
}