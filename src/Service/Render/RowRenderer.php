<?php

namespace App\Service\Render;

use App\Dto\Render\ElementData;
use App\Dto\Row;

class RowRenderer
{
    private ElementData $renderData;

    public function __construct(
        private readonly CellRenderer $cellRenderer
    ) {
    }

    public function render(Row $row): ElementData
    {
        $this->renderData = new ElementData('tr');

        foreach($row->cells as $index => $cell) {
            $cellRenderData = $this->cellRenderer->render($cell);
            $this->renderData->addChild($index, $cellRenderData);
        }

        return $this->renderData;
    }
}