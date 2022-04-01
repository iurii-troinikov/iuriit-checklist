<?php

declare(strict_types=1);

namespace App\Model\Animal;

abstract class Cattle implements Animal
{
    public function voice(): void
    {
        echo 'Cattle make sounds';
    }

    public function move(): void
    {
        echo 'Cattle are moving around';
    }
}
