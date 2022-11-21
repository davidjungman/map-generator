<?php

namespace App\Dto;

class Map
{
    /** @param Row[] $rows */
    public function __construct(
        public readonly array $rows,
    ) {
    }
}