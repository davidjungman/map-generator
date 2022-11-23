<?php

namespace App\Dto\Render;

class RenderData implements Renderable
{
    public function __construct(
        public string $renderString = ""
    ) {
    }

    public function getRenderString(): string
    {
        return $this->renderString;
    }

    public function appendChild(Renderable $childElement): self
    {
        $this->append($childElement->getRenderString());

        return $this;
    }

    public function appendImage(ImageRenderData $imageRenderData): self
    {
        $this->append($imageRenderData->getRenderString());

        return $this;
    }

    public function append(string $renderString): self
    {
        $this->renderString .= $renderString . " ";

        return $this;
    }

    public function startOpeningElement(string $element): self
    {
        $string = "<" . $element;
        $this->append($string);

        return $this;
    }

    public function openStyle(): self
    {
        $this->append("style='");

        return $this;
    }

    public function addStyle(string $attribute, string $value): self
    {
        $styleString = $attribute . ": " . $value . ';';
        $this->append($styleString);

        return $this;
    }

    public function setValue(string $value): self
    {
        $this->append($value);

        return $this;
    }

    public function closeStyle(): self
    {
        $this->append("'");

        return $this;
    }

    public function closeOpeningElement(): self
    {
        $this->append(">");

        return $this;
    }

    public function closeElement(string $element): self
    {
        $string = "</" . $element . ">";
        $this->append($string);

        return $this;
    }
}