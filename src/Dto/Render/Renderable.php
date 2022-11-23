<?php

namespace App\Dto\Render;

interface Renderable
{
    public function getRenderString(): string;
}