<?php

declare(strict_types=1);

namespace App\Model\Plant;

class Rose extends Flower
{
    public string $fragrant;
    public string $beautiful;

    public function sting(): void
    {
        echo 'The rose stings';
    }
}
