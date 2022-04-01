<?php

declare(strict_types=1);

namespace App\Model\Animal;

abstract class WildAnimal implements Animal
{
    public function voice(): void
    {
        echo 'WildAnimal make sounds';
    }

    public function move(): void
    {
        echo 'WildAnimal are moving around';
    }
}
