<?php

namespace App\Enum\Render;

enum Asset: string
{
    case ELEVATION_POSITIVE_ONE = 'elevation/elevation-positive-one.png';
    case ELEVATION_POSITIVE_TWO = 'elevation/elevation-positive-two.png';

    case DEAD_BODY_VARIANT_A = 'deadbody/deadbody-variant-a.png';
    case DEAD_BODY_VARIANT_B = 'deadbody/deadbody-variant-b.png';
    case DEAD_BODY_VARIANT_C = 'deadbody/deadbody-variant-c.png';

    case HOT_ROCK_VARIANT_A = 'hot-rock/hot-rock-variant-a.png';
    case HOT_ROCK_VARIANT_B = 'hot-rock/hot-rock-variant-b.png';

    case LAVA = 'lava/lava.png';

    /** @return string[] */
    public static function getDeadBody(): array
    {
        return [
            self::DEAD_BODY_VARIANT_A,
            self::DEAD_BODY_VARIANT_B,
            self::DEAD_BODY_VARIANT_C
        ];
    }

    public static function getHotRock(): array
    {
        return [
            self::HOT_ROCK_VARIANT_A,
            self::HOT_ROCK_VARIANT_B
        ];
    }
}