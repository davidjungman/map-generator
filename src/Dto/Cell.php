<?php

namespace App\Dto;

use App\Enum\BorderCellType;

class Cell
{
    /** @param BorderCellType[] $borderTypes */
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly bool $isBorderCell,
        public readonly ?array $borderTypes = null
    ) {
    }

    public function render(): string
    {
        $string = "";

        $string .= "<td style='";
        if ($this->isBorderCell === true) {
            $this->renderWithBorderType($string);
        }

        return $string . "'></td>";
    }

    private function renderWithBorderType(string &$string): void
    {
        if ($this->borderTypes === null) {
            throw new \Exception('Cell can\'t be borderCell without Border Type');
        }

        foreach($this->borderTypes as $borderType) {
            switch($borderType) {
                case BorderCellType::LEFT:
                    $string .= "border-left:1px solid black;";
                    break;
                case BorderCellType::TOP:
                    $string .= "border-top: 1px solid black;";
                    break;
                case BorderCellType::BOTTOM:
                    $string .= "border-bottom: 1px solid black;";
                    break;
                case BorderCellType::RIGHT:
                    $string .= "border-right: 1px solid black;";
                    break;
            }
        }
    }
}