<?php

namespace App\Service\Render;

use App\Dto\Render\CssStyle;
use App\Dto\Render\ImageRenderData;

class ImageRenderer
{
    public const IMAGE_DIR = 'assets/';

    public function render(CssStyle $cssStyle): ImageRenderData
    {
        $renderData = new ImageRenderData(
            self::IMAGE_DIR . $cssStyle->value,
            35,
            35
        );

        return $renderData;
    }
}