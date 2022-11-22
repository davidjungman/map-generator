<?php

namespace App\Dto\Render;

use App\Enum\Render\Style;

class CssStyle
{
    public function __construct(
        public readonly Style $style,
        public readonly string $value
    ) {
    }

    public static function of(Style $style, string $value): CssStyle
    {
        return new self($style, $value);
    }
}