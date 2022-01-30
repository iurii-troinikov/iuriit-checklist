<?php

declare(strict_types=1);

namespace App\Model\Animal;

abstract class Pet implements Animal
{
    public function voice(): void
    {
        echo 'Pets make sounds';
    }

    public function move(): void
    {
        echo 'Pets are moving around';
    }
}
