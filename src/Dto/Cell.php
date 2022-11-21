<?php

namespace App\Dto;

class Cell
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $outerCell
    ) {
    }

    public function render(): string
    {
        return "<td></td>";
    }
}