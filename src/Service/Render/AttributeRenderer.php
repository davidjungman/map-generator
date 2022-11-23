<?php

namespace App\Service\Render;

use App\Dto\Attribute\Attribute;
use App\Dto\Render\CssStyle;
use App\Enum\AttributeType;
use App\Enum\Render\Style;

class AttributeRenderer
{
    public function render(Attribute $attribute): CssStyle
    {
        switch($attribute->attributeType){
            case AttributeType::DEAD_BODY:
                return CssStyle::of(Style::BACKGROUND_COLOR, 'green');
            case AttributeType::HOT_ROCK:
                return CssStyle::of(Style::BACKGROUND_COLOR, 'yellow');
            case AttributeType::ELEVATION:
                return CssStyle::of(Style::BACKGROUND_COLOR, 'grey');
        }

        throw new \Exception('Unknown attribute.');
    }
}