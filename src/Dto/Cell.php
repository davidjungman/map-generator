<?php

namespace App\Dto;

class Cell
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isBorderCell
    ) {
    }

    public function render(): string
    {
        if ($this->isBorderCell === true ){
            return "<td style='background:red;'></td>";
        }
        return "<td></td>";
    }
}