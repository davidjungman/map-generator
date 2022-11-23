<?php

namespace App\Enum\Render;

enum Style: string
{
    case BACKGROUND_COLOR = "background-color";
    case BORDER_LEFT = "border-left";
    case BORDER_RIGHT = "border-right";
    case BORDER_TOP = "border-top";
    case BORDER_BOTTOM = "border-bottom";
    case BACKGROUND_IMAGE = "background-image";
}