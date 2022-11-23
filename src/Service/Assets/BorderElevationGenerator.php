<?php

namespace App\Service\Assets;

use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Cell;
use App\Dto\Detector\PathChunk;
use App\Dto\Path\DirectPath;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Enum\Coordinate;
use App\Helper\CellSorter;
use App\Service\Calculator\ChanceCalculator;
use App\Service\Calculator\DirectPathCalculator;
use App\Service\CellAccessor;
use App\Service\Detector\PathChunkDetector;

class BorderElevationGenerator implements AssetGenerator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor,
        private readonly ChanceCalculator $chanceCalculator,
        private readonly PathChunkDetector $pathChunkDetector,
        private readonly DirectPathCalculator $directPathCalculator,
        private readonly int $weight,
        private readonly int $maxCliffLevel
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $this->generatePath(
            start: new Coordinates(0, 0),
            end: new Coordinates($mapSetting->rowCount, 0)
        );

        /*
        $this->generatePath(
            start: new Coordinates(0, $mapSetting->columnCount),
            end: new Coordinates($mapSetting->rowCount, $mapSetting->columnCount)
        );
        */
    }

    private function generatePath(Coordinates $start, Coordinates $end, int $currentCliffLevel = 1): void
    {
        $startCell = $this->cellAccessor->get($start->x, $start->y);
        $endCell = $this->cellAccessor->get($end->x, $end->y);
        $path = $this->directPathCalculator->calculate($startCell, $endCell);

        $cells = $path->getCells();
        $path = new DirectPath($cells, $path->direction);

        foreach($path->getCells() as $cell) {
            $this->createAttribute($cell);
        }

        $chunks = $this->pathChunkDetector->detect($path);

        if (\count($chunks) === 0) {
            return;
        }

        foreach($chunks as $chunk) {
            $this->generateCliff($chunk, $currentCliffLevel);
        }
    }

    private function generateCliff(PathChunk $pathChunk, int $currentCliffLevel): void
    {
        if ($currentCliffLevel >= $this->maxCliffLevel) {
            return;
        }

        $cells = $pathChunk->cells;
        usort($cells, CellSorter::sortCellsByXCoordinate());

        $firstCell = $cells[0];
        $firstCliffCell = $this->cellAccessor->get($firstCell->x+1, $firstCell->y+1);

        $totalCells = \count($cells);
        $lastCell = $cells[$totalCells-2];
        $lastCliffCell = $this->cellAccessor->get($lastCell->x, $lastCell->y);

        $this->generatePath(
            start: new Coordinates($firstCliffCell->x, $firstCliffCell->y),
            end: new Coordinates($lastCliffCell->x, $lastCliffCell->y),
            currentCliffLevel: $currentCliffLevel +1
        );
    }

    private function generateRightNeighboringCells(Cell $rootCell, int $width): void
    {
        if ($width === 0) {
            return;
        }

        $neighbor = $this->cellAccessor->get($rootCell->x, $rootCell->y + 1);
        if ($neighbor !== null) {
            $this->createAttribute($neighbor);
            $width--;
            $this->generateRightNeighboringCells($neighbor, $width);
        }
    }

    private function createAttribute(Cell $cell): void
    {
        $attribute = new ElevationAttribute(1);
        $cell->addAttribute($attribute);
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}