<?php

namespace App\Dto\Path;

use App\Dto\Cell;

interface Path extends \Countable
{
    /** @return Cell[] */
    public function getCells(): array;
}