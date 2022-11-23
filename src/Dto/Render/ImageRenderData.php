<?php

namespace App\Dto\Render;

class ImageRenderData implements Renderable
{
    public function __construct(
        public readonly string $assetPath,
        public readonly float $height,
        public readonly float $width
    ) {
    }

    public function getRenderString(): string
    {
        return "<img 
            src=\"{$this->assetPath}\"
            style=\"{$this->renderCss()}\"
            alt='asset'
         />";
    }

    public function renderCss(): string
    {
        return "height: {$this->height}px; width: {$this->width}px";
    }
}