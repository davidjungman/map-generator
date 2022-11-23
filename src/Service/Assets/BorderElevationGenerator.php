<?php

namespace App\Service\Assets;

use App\Dto\Attribute\ElevationAttribute;
use App\Dto\Cell;
use App\Dto\Cliff\CliffSize;
use App\Dto\Detector\PathChunk;
use App\Dto\Path\DirectPath;
use App\Dto\Path\PathData;
use App\Dto\Path\PathSizeData;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Enum\BorderType;
use App\Service\Calculator\CliffCalculator;
use App\Service\Calculator\DirectPathCalculator;
use App\Service\CellAccessor;
use App\Service\Detector\PathChunkDetector;

class BorderElevationGenerator implements AssetGenerator
{
    public function __construct(
        private readonly CellAccessor $cellAccessor,
        private readonly PathChunkDetector $pathChunkDetector,
        private readonly DirectPathCalculator $directPathCalculator,
        private readonly CliffCalculator $cliffCalculator,
        private readonly int $weight,
        private readonly int $maxCliffLevel
    ) {
    }

    public function generate(MapSetting $mapSetting): void
    {
        $leftRootPath = $this->generatePathSize(0, $mapSetting->rowCount);
        $leftPathData = PathData::fromCells(
            $this->cellAccessor->get($leftRootPath->min, 0),
            $this->cellAccessor->get($leftRootPath->max, 0)
        );
        $this->generatePath(
            pathData: $leftPathData,
            border: BorderType::LEFT
        );

        $rightRootPath = $this->generatePathSize(0, $mapSetting->rowCount);
        $rightPathData = PathData::fromCells(
            $this->cellAccessor->get($rightRootPath->min, $mapSetting->columnCount),
            $this->cellAccessor->get($rightRootPath->max, $mapSetting->columnCount),
        );
        $this->generatePath(
            pathData: $rightPathData,
            border: BorderType::RIGHT
        );

        $topRootPath = $this->generatePathSize(0, $mapSetting->columnCount);
        $topPathData = PathData::fromCells(
            $this->cellAccessor->get(0, $topRootPath->min),
            $this->cellAccessor->get(0, $topRootPath->max)
        );
        $this->generatePath(
            pathData: $topPathData,
            border: BorderType::TOP
        );

        $bottomRootPath = $this->generatePathSize(0, $mapSetting->columnCount);
        $bottomPathData = PathData::fromCells(
            $this->cellAccessor->get($mapSetting->rowCount, $bottomRootPath->min),
            $this->cellAccessor->get($mapSetting->rowCount, $bottomRootPath->max)
        );
        $this->generatePath(
            pathData: $bottomPathData,
            border: BorderType::BOTTOM
        );
    }

    private function generatePathSize(
        int $min,
        int $max
    ): PathSizeData {
        $start = random_int($min, $max / 2);
        $end = random_int($start, $max - $start);

        return new PathSizeData($start, $end);
    }

    private function generatePath(PathData $pathData, BorderType $border, int $currentCliffLevel = 1): void
    {
        $startCell = $this->cellAccessor->get($pathData->start->x, $pathData->start->y);
        $endCell = $this->cellAccessor->get($pathData->end->x, $pathData->end->y);
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
            if ($currentCliffLevel >= $this->maxCliffLevel) {
                continue;
            }

            $cliffData = $this->cliffCalculator->calculate($chunk, $border);
            $this->generatePath(
                pathData: PathData::fromCells($cliffData->cliffStartCell, $cliffData->cliffEndCell),
                border: $border,
                currentCliffLevel: $currentCliffLevel + 1
            );
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