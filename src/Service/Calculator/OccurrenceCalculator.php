<?php

namespace App\Service\Calculator;

class OccurrenceCalculator
{
    public function calculate(float $minChance, float $maxChance, int $totalFields): int
    {
        $totalFieldsInPercentage = $totalFields / 100;
        $chance = random_int($minChance*100, $maxChance*100) / 100;
        $occurrences = $totalFieldsInPercentage * $chance;

        return round($occurrences, 0, PHP_ROUND_HALF_UP);
    }
}