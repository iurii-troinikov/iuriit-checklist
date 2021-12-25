<?php

declare(strict_types=1);

namespace App\Model\Plant;

class Birch extends Tree
{
    public string $deciduous;
    public string $strong;

    public function cry(): void
        {
            echo 'Еhe birch is crying';
        }
}
