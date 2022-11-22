<?php

namespace App\Service\Render;

use App\Dto\Map;
use App\Dto\Render\ElementData;

class GridRenderer
{
    private ElementData $renderData;

    public function __construct(
        private readonly RowRenderer $rowRenderer
    ) {
    }

    public function render(Map $map): ElementData
    {
        $this->renderData = new ElementData('table');

        foreach($map->rows as $index => $row) {
            $rowData = $this->rowRenderer->render($row);
            $this->renderData->addChild($index, $rowData);
        }

        return $this->renderData;
    }
}