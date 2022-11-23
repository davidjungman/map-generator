<?php

namespace App\Enum;

enum BorderType: int
{
    case LEFT = 0;
    case RIGHT = 1;
    case TOP = 2;
    case BOTTOM = 3;
}