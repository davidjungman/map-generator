<?php

namespace App\Service;

use App\Dto\Map;
use App\Dto\Utils\Coordinates;
use App\Dto\Utils\MapSetting;
use App\Service\Assets\AssetGenerator;
use App\Service\CellAccessor\CellAccessorInterface;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class MapGenerator
{
    public function __construct(
        private readonly CellGenerator $cellGenerator,
        private readonly RowGenerator $rowGenerator,
        private readonly RewindableGenerator $cellAccessors,
        private readonly RewindableGenerator $assetGenerators
    ) {
    }

    public function generate(int $rowCount, int $columnCount): Map
    {
        $rowCount--;
        $columnCount--;
        $mapSetting = new MapSetting($rowCount, $columnCount);

        $rows = [];
        for ($rowIndex = 0; $rowIndex <= $rowCount; $rowIndex++) {
            $cells = [];
            for($columnIndex = 0; $columnIndex <= $columnCount; $columnIndex++) {
                $coordinates = new Coordinates($rowIndex, $columnIndex);
                $cells[] = $this->cellGenerator->generate($coordinates, $mapSetting);
            }
            $rows[] = $this->rowGenerator->generate($cells);
        }

        $map = new Map($rows);

        foreach($this->getAssetGenerators() as $assetGenerator) {
            $this->updateCellAccessors($map, $mapSetting);
            $assetGenerator->generate($mapSetting);
        }

        return $map;
    }

    private function updateCellAccessors(Map $map, MapSetting $mapSetting): void
    {
        foreach($this->cellAccessors as $cellAccessor) {
            $cellAccessor->build($map, $mapSetting);
        }
    }

    /** @return AssetGenerator[] */
    private function getAssetGenerators(): array
    {
        $generators = [];
        foreach($this->assetGenerators as $assetGenerator) {
            $generators[] = $assetGenerator;
        }

        usort($generators, function(AssetGenerator $assetGeneratorA, AssetGenerator $assetGeneratorB) {
            if ($assetGeneratorA->getWeight() === $assetGeneratorB->getWeight()) {
                return 0;
            }

            return $assetGeneratorA->getWeight() > $assetGeneratorB->getWeight() ? 1 : -1;
        });


        return $generators;
    }
}