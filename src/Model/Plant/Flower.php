<?php

declare(strict_types=1);

namespace App\Model\Plant;

abstract class Flower implements Plant
{
    public function bloom(): void
    {
        echo 'the flower is blooming';
    }
    public function grow(): void
    {
        echo "Growing";
    }
    public function photosynthesis(): void
    {
        echo "Photosynthesising";
    }
}
