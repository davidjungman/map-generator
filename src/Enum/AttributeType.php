<?php

namespace App\Enum;

enum AttributeType: int
{
    case DEAD_BODY = 0;
    case LAVA = 1;
    case HOT_ROCK = 2;
    case ELEVATION = 3;
}