<?php

namespace App\Service\Assets;

use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Cell;
use App\Dto\Cliff\CliffSize;
use App\Dto\Detector\PathChunk;
use App\Dto\Path\DirectPath;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Enum\BorderType;
use App\Service\Calculator\DirectPathCalculator;
use App\Service\CellAccessor;
use App\Service\Detector\PathChunkDetector;

class BorderElevationGenerator implements AssetGenerator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor,
        private readonly PathChunkDetector $pathChunkDetector,
        private readonly DirectPathCalculator $directPathCalculator,
        private readonly int $weight,
        private readonly int $maxCliffLevel
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $leftCliff = $this->generateCliffSize(0, $mapSetting->rowCount);
        $this->generatePath(
            start: new Coordinates($leftCliff->start, 0),
            end: new Coordinates($leftCliff->end, 0),
            border: BorderType::LEFT
        );

        $rightCliff = $this->generateCliffSize(0, $mapSetting->rowCount);
        $this->generatePath(
            start: new Coordinates($rightCliff->start, $mapSetting->columnCount),
            end: new Coordinates($rightCliff->end, $mapSetting->columnCount),
            border: BorderType::RIGHT
        );

        $topCliff = $this->generateCliffSize(0, $mapSetting->columnCount);
        $this->generatePath(
            start: new Coordinates(0, $topCliff->start),
            end: new Coordinates(0, $topCliff->end),
            border: BorderType::TOP
        );

        $bottomCliff = $this->generateCliffSize(0, $mapSetting->columnCount);
        $this->generatePath(
            start: new Coordinates($mapSetting->rowCount, $bottomCliff->start),
            end: new Coordinates($mapSetting->rowCount, $bottomCliff->end),
            border: BorderType::BOTTOM
        );
    }

    private function generateCliffSize(int $min, int $max): CliffSize
    {
        $cliffStart = random_int($min, $max / 2);
        $cliffEnd = random_int($cliffStart, $max - $cliffStart);

        return new CliffSize($cliffStart, $cliffEnd);
    }

    private function generatePath(Coordinates $start, Coordinates $end, BorderType $border, int $currentCliffLevel = 1): void
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
            $this->generateCliff($chunk, $currentCliffLevel, $border);
        }
    }

    private function generateCliff(PathChunk $pathChunk, int $currentCliffLevel, BorderType $border): void
    {
        if ($currentCliffLevel >= $this->maxCliffLevel) {
            return;
        }

        $cells = $pathChunk->cells;
        $firstCell = $cells[1];
        $firstCliffCell = $this->calculateCliffCell($firstCell, $border);

        $totalCells = \count($cells);
        $lastCell = $cells[$totalCells-2];
        $lastCliffCell = $this->calculateCliffCell($lastCell, $border);

        $this->generatePath(
            start: new Coordinates($firstCliffCell->x, $firstCliffCell->y),
            end: new Coordinates($lastCliffCell->x, $lastCliffCell->y),
            border: $border,
            currentCliffLevel: $currentCliffLevel + 1
        );
    }

    private function calculateCliffCell(Cell $firstCell, BorderType $border): Cell
    {
        switch($border) {
            case BorderType::LEFT:
                return $this->cellAccessor->getRightNeighbor($firstCell);
            case BorderType::RIGHT:
                return $this->cellAccessor->getLeftNeighbor($firstCell);
            case BorderType::TOP:
                return $this->cellAccessor->getBottomNeighbor($firstCell);
            case BorderType::BOTTOM:
                return $this->cellAccessor->getTopNeighbor($firstCell);
        }

        throw new \Exception("invalid borderType");
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