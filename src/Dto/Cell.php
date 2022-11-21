<?php

namespace App\Dto;

use App\Dto\Attribute\Attribute;
use App\Dto\Attribute\AttributeWithValue;
use App\Enum\BorderCellType;

class Cell
{
    /** @var string[] */
    public array $css = [];

    /**
     * @param BorderCellType[] $borderTypes
     * @param Attribute[] $attributes
     */
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isBorderCell,
        public readonly ?array $borderTypes = null,
        public array $attributes = [],
        public bool $occupied = false
    ) {
    }

    public function addAttribute(Attribute $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    public function setOccupied(): void
    {
        $this->occupied = true;
    }

    public function render(): string
    {
        $css = $this->renderCss();
        $value = $this->renderValue();

        return "<td $css>$value</td>";
    }

    private function renderCss(): string
    {
        if ($this->isBorderCell === true) {
            $this->renderWithBorderType();
        }

        if (\count($this->attributes) > 0) {
            $this->renderWithAttributes();
        }

        $css = "style='";
        foreach($this->css as $style) {
            $css .= $style;
        }
        $css .= "'";

        return $css;
    }

    private function renderValue(): string
    {
        $value = "";
        foreach($this->attributes as $attribute) {
            if ($attribute instanceof AttributeWithValue) {
                $value .= $attribute->renderValue();
            }
        }

        return $value;
    }

    private function renderWithBorderType(): void
    {
        if ($this->borderTypes === null) {
            throw new \Exception('Cell can\'t be borderCell without Border Type');
        }

        foreach($this->borderTypes as $borderType) {
            switch($borderType) {
                case BorderCellType::LEFT:
                    $this->css[] = "border-left:1px solid black;";
                    break;
                case BorderCellType::TOP:
                    $this->css[] = "border-top: 1px solid black;";
                    break;
                case BorderCellType::BOTTOM:
                    $this->css[] = "border-bottom: 1px solid black;";
                    break;
                case BorderCellType::RIGHT:
                    $this->css[] = "border-right: 1px solid black;";
                    break;
            }
        }
    }

    private function renderWithAttributes(): void
    {
        foreach($this->attributes as $attribute) {
            $this->css[] = $attribute->renderCss();
        }
    }
}