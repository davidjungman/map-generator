<?php

namespace App\Service\Assets;

use App\Dto\Attribute\ElevationAttribute;

class RockGenerator extends AbstractOccurrenceGenerator implements AssetGenerator
{
    public function createAttribute(): void
    {
        $cell = $this->cellAccessor->randomUnoccupiedExceptBorderCells();
        $attribute = new ElevationAttribute(1);

        $cell->addAttribute($attribute);
        $cell->setOccupied();
    }
}