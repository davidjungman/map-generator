<?php

namespace App\Service\Calculator;

use App\Dto\Cell;
use App\Dto\Utils\Coordinates;
use App\Service\CellAccessor;

class BorderCalculator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor
    ) {
    }

    /**
     * @return Cell[]
     */
    public function calculate(array $poolCells,Coordinates $mapSettingCoordinates):array{

        $hotRocks = [];

        foreach ($poolCells as $cell){
            $right = $this->cellAccessor->getRightNeighbor($cell);
            $top = $this->cellAccessor->getTopNeighbor($cell);
            $left = $this->cellAccessor->getLeftNeighbor($cell);
            $bottom = $this->cellAccessor->getBottomNeighbor($cell);

            if ($right !== null){
                if ($right->isOccupied() === false){
                    $hotRocks[] = $right;
                }
            }
            if ($top !== null){
                if ($top->isOccupied() === false){
                    $hotRocks[] = $top;
                }
            }if ($left !== null){
                if ($left->isOccupied() === false){
                    $hotRocks[] = $left;
                }
            }if ($bottom !== null){
                if ($bottom->isOccupied() === false){
                    $hotRocks[] = $bottom;
                }
            }
        }

        return $hotRocks;
    }
}