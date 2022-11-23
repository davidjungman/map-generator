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

        /** @var PathChunk[] $savedChunks */
        $savedChunks = [];

        foreach($cells as $cell)
        {
            $nextCells = array_slice($cells, 1);
            $currentChunk = [];
            $currentChunk[] = $cell;

            foreach($nextCells as $nextCell) {
                if ($cell->x + \count($currentChunk) === $nextCell->x) {
                    $currentChunk[] = $nextCell;
                } else {
                    break;
                }
            }
            if (\count($currentChunk) >= self::MIN_CHUNK_SIZE) {
                $savedChunks[] = new PathChunk($currentChunk);
                array_splice($cells, 0, \count($currentChunk));
            }
        }

        return $savedChunks;
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

    /**
     * @param Cell[] $sortedCells
     * @return PathChunk[]
     */
    private function detectForPath(array $sortedCells, callable $addToChunk): array
    {
        /** @var PathChunk[] $savedChunks */
        $savedChunks = [];

        foreach($sortedCells as $cell)
        {
            $nextCells = array_slice($sortedCells, 1);
            $currentChunk = [];
            $currentChunk[] = $cell;

            foreach($nextCells as $nextCell) {
                if ($addToChunk($cell, \count($currentChunk), $nextCell)) {
                    $currentChunk[] = $nextCell;
                } else {
                    break;
                }
            }
            if (\count($currentChunk) >= self::MIN_CHUNK_SIZE) {
                $savedChunks[] = new PathChunk($currentChunk);
                array_splice($sortedCells, 0, \count($currentChunk));
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