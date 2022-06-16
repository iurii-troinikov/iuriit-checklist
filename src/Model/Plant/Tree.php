<?php

declare(strict_types=1);

namespace App\Model\Plant;

abstract class Tree implements Plant
{
    public function throwOffTheLeaves(): void
    {
        echo 'Throw off the leaves';
    }
    public function grow(): void
    {
        echo 'Growing...';
    }
    public function photosynthesis(): void
    {
        echo 'Photosynthesis...';
    }
}
