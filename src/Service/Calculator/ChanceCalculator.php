<?php

namespace App\Service\Calculator;

class ChanceCalculator
{
    /** @param array<int, float> $chances */
    public function calculate(array $chances): int
    {
        $value = random_int(0,10_000);
        $currentChance = 0;
        foreach($chances as $tier => $chance) {
            $chanceFloat = (float) $chance;
            $currentChance += $chanceFloat * 100;
            if ($value <= $currentChance) {
                return $tier;
            }
        }


        throw new \Exception('Total is exceeding 100%');
    }
}