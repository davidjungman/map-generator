<?php

namespace App\Service\Detector;

use App\Dto\Cell;
use App\Dto\Detector\PathChunk;
use App\Dto\Path\DirectPath;
use App\Dto\Path\Path;
use App\Enum\Coordinate;
use App\Helper\CellSorter;

/** Detects continual parts of path */
class PathChunkDetector
{
    private const MIN_CHUNK_SIZE = 3;

    /**
     * @return PathChunk[]
     */
    public function detect(Path $path): array
    {
        // TODO: currently supports only direct path
        if (!$path instanceof DirectPath) {
            throw new \Exception('Not implemented.');
        }

        if (!$this->isPathLongerThenMinimumChunkSize($path)) {
            return [];
        }

        $direction = $path->direction;
        if ($direction === Coordinate::Y) {
            return $this->detectForVerticalPath($path);
        } else {
            return $this->detectForHorizontalPath($path);
        }
    }

    /** @return PathChunk[] */
    private function detectForVerticalPath(Path $path): array
    {
        $cells = $path->getCells();

        usort($cells, CellSorter::sortCellsByXCoordinate());

        return $this->detectForPath($cells, $this->addToChunkForX());
    }

    /** @return PathChunk[] */
    private function detectForHorizontalPath(Path $path): array
    {
        $cells = $path->getCells();

        usort($cells, CellSorter::sortCellsByYCoordinate());

        return $this->detectForPath($cells, $this->addToChunkForY());
    }

    private function addToChunkForY(): callable
    {
        return function(Cell $cell, int $chunkSize, Cell $nextCell) {
            if ($cell->y + $chunkSize === $nextCell->y) {
                return true;
            }
            return false;
        };
    }

    private function addToChunkForX(): callable
    {
        return function(Cell $cell, int $chunkSize, Cell $nextCell) {
            if ($cell->x + $chunkSize === $nextCell->x) {
                return true;
            }
            return false;
        };
    }

    /**
     * @param Cell[] $sortedCells
     * @return PathChunk[]
     */
    private function detectForPath(array $sortedCells, callable $addToChunk): array
    {
        /** @var PathChunk[] $savedChunks */
        $savedChunks = [];

        $cellsToSelectFrom = $sortedCells;

        foreach($sortedCells as $cell)
        {
            if (\count($cellsToSelectFrom) === 0){
                break;
            }

            array_splice($cellsToSelectFrom, 0, 1);
            $currentChunk = [];
            $currentChunk[] = $cell;

            foreach($cellsToSelectFrom as $nextCell) {
                if ($addToChunk($cell, \count($currentChunk), $nextCell)) {
                    $currentChunk[] = $nextCell;
                } else {
                    break;
                }
            }

            if (\count($currentChunk) >= self::MIN_CHUNK_SIZE) {
                $savedChunks[] = new PathChunk($currentChunk);
            }
        }

        return $savedChunks;
    }

    private function isPathLongerThenMinimumChunkSize(Path $path): bool
    {
        if (\count($path) < self::MIN_CHUNK_SIZE) {
            return false;
        }

        return true;
    }
}