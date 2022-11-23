<?php

namespace App\Service\Render;

use App\Dto\Attribute\Attribute;
use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Render\CssStyle;
use App\Enum\AttributeType;
use App\Enum\Render\Asset;
use App\Enum\Render\Style;

class AttributeRenderer
{
    /** @return CssStyle[] */
    public function render(Attribute $attribute): array
    {
        $styles = [];

        switch($attribute->attributeType){
            case AttributeType::DEAD_BODY:
                $options = Asset::getDeadBody();
                $styles[] = CssStyle::of(Style::BACKGROUND_IMAGE, $this->random($options)->value);
                $styles[] = CssStyle::of(Style::BACKGROUND_COLOR, '#1abc9c');
                break;
            case AttributeType::HOT_ROCK:
                $options = Asset::getHotRock();
                $styles[] = CssStyle::of(Style::BACKGROUND_COLOR, '#f1c40f');
                $styles[] = CssStyle::of(Style::BACKGROUND_IMAGE, $this->random($options)->value);
                break;
            case AttributeType::ELEVATION:
                /** @var ElevationAttribute $attribute */
                $elevation = $attribute->elevation;
                switch($elevation) {
                    case 1:
                        $styles[] = CssStyle::of(Style::BACKGROUND_IMAGE, Asset::ELEVATION_POSITIVE_ONE->value);
                        $styles[] = CssStyle::of(Style::BACKGROUND_COLOR, '#bdc3c7');
                        break;
                    case 2:
                        $styles[] = CssStyle::of(Style::BACKGROUND_IMAGE, Asset::ELEVATION_POSITIVE_TWO->value);
                        $styles[] = CssStyle::of(Style::BACKGROUND_COLOR, '#95a5a6');
                        break;
                }
        }

        return $styles;
    }

    /** @param Asset[] $options */
    public function random(array $options): Asset
    {
        $key = array_rand($options);
        return $options[$key];
    }
}