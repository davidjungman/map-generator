<?php

namespace App\Dto\Render;

class ElementData
{
    /** @var CssStyle[] */
    public array $styles = [];

    public readonly ?string $element;

    /** @var ElementData[] */
    public array $childElements = [];

    public ?string $value;

    public function __construct(
        ?string $element,
        ?string $value = null
    ) {
        $this->element = $element;
        $this->value = $value;
    }

    public function addStyle(CssStyle $style): self
    {
        $this->styles[] = $style;

        return $this;
    }

    public function overrideValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function addChild(int $index, ElementData $elementData): self
    {
        $this->childElements[$index] = $elementData;

        return $this;
    }
}