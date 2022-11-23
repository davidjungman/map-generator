<?php

namespace App\Service\Render;

use App\Dto\Map;
use App\Dto\Render\CssStyle;
use App\Dto\Render\ElementData;
use App\Dto\Render\RenderData;
use App\Enum\Render\Style;

class Renderer
{
    public function __construct(
        private readonly GridRenderer $gridRenderer,
        private readonly ImageRenderer $imageRenderer
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

        // TODO: Currently not supporting multiple images
        $imageElement = null;

        foreach($elementData->styles as $cssStyle) {
            if ($cssStyle->style === Style::BACKGROUND_IMAGE) {
                $imageElement = $cssStyle;
            } else {
                $elementRenderData->addStyle($cssStyle->style->value, $cssStyle->value);
            }
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

        if ($imageElement !== null) {
            $imageRenderData = $this->imageRenderer->render($imageElement);
            $elementRenderData->appendChild($imageRenderData);
        }

        $elementRenderData->closeElement($elementData->element);

        return $elementRenderData;
    }

    private function getImageRenderData(CssStyle $cssStyle): RenderData
    {
        $assetPath = "\"./assets/$cssStyle->value\"";
        $imageElement = '<img src='. $assetPath .'/>';

        return new RenderData($imageElement);
    }
}