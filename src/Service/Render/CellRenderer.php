<?php

namespace App\Service\Render;

use App\Dto\Cell;
use App\Dto\Render\CssStyle;
use App\Dto\Render\ElementData;
use App\Enum\BorderType;
use App\Enum\Render\Style;

class CellRenderer
{
    private ElementData $renderData;

    public function __construct(
        private readonly AttributeRenderer $attributeRenderer
    ) {
    }

    public function render(Cell $cell): ElementData
    {
        $this->renderData = new ElementData('td');

        if ($cell->isBorderCell === true) {
            $this->renderWithBorderType($cell);
        }

        if (\count($cell->attributes) > 0) {
            $this->renderWithAttributes($cell);
        }

        if ($cell->valueAttribute !== null) {
            $this->renderData->overrideValue($cell->valueAttribute->getValue());
        }

        return $this->renderData;
    }

    private function renderWithBorderType(Cell $cell): void
    {
        if ($cell->borderTypes === null) {
            throw new \Exception('Cell can\'t be borderCell without Border Type');
        }

        foreach($cell->borderTypes as $borderType) {
            switch($borderType) {
                case BorderType::LEFT:
                    $this->renderData->addStyle(CssStyle::of(Style::BORDER_LEFT, "1px solid black"));
                    break;
                case BorderType::TOP:
                    $this->renderData->addStyle(CssStyle::of(Style::BORDER_TOP, "1px solid black"));
                    break;
                case BorderType::BOTTOM:
                    $this->renderData->addStyle(CssStyle::of(Style::BORDER_BOTTOM, "1px solid black"));
                    break;
                case BorderType::RIGHT:
                    $this->renderData->addStyle(CssStyle::of(Style::BORDER_RIGHT, "1px solid black"));
                    break;
            }
        }
    }

    private function renderWithAttributes(Cell $cell): void
    {
        foreach($cell->attributes as $attribute)
        {
            $cssStyles = $this->attributeRenderer->render($attribute);
            foreach($cssStyles as $cssStyle) {
                $this->renderData->addStyle($cssStyle);
            }
        }
    }
}