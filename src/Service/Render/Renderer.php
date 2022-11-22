<?php

namespace App\Service\Render;

use App\Dto\Map;
use App\Dto\Render\ElementData;
use App\Dto\Render\RenderData;

class Renderer
{
    public function __construct(
        private readonly GridRenderer $gridRenderer
    ) {
    }

    public function render(Map $map): string
    {
        $renderData = $this->gridRenderer->render($map);
        return $this->renderElement($renderData)->getRenderString();
    }

    private function renderElement(ElementData $elementData): RenderData
    {
        $elementRenderData = new RenderData();
        $elementRenderData
            ->startOpeningElement($elementData->element)
            ->openStyle();

        foreach($elementData->styles as $cssStyle) {
            $elementRenderData->addStyle($cssStyle->style->value, $cssStyle->value);
        }

        $elementRenderData
            ->closeStyle()
            ->closeOpeningElement();

        if ($elementData->value !== null) {
            $elementRenderData->setValue($elementData->value);
        }

        if (\count($elementData->childElements) !== 0) {
            foreach($elementData->childElements as $childElement) {
                $childElementRenderData = $this->renderElement($childElement);
                $elementRenderData->appendChild($childElementRenderData);
            }
        }

        $elementRenderData->closeElement($elementData->element);

        return $elementRenderData;
    }
}