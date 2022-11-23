<?php

namespace App\Enum\Render;

enum Asset: string
{
    case ELEVATION_POSITIVE_ONE = 'elevation/elevation-positive-one.png';
    case HOT_ROCK = 'hot-rock/hot-rock.png';

    case DEAD_BODY_VARIANT_A = 'deadbody/deadbody-variant-a.png';
    case DEAD_BODY_VARIANT_B = 'deadbody/deadbody-variant-b.png';
    case DEAD_BODY_VARIANT_C = 'deadbody/deadbody-variant-c.png';

    /** @return string[] */
    public static function getDeadBody(): array
    {
        return [
            self::DEAD_BODY_VARIANT_A,
            self::DEAD_BODY_VARIANT_B,
            self::DEAD_BODY_VARIANT_C
        ];
    }
}