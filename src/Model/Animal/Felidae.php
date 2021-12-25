<?php

declare(strict_types=1);

namespace App\Model\Animal;

abstract class Felidae implements Animal
{
    public function voice(): void
    {
        echo 'Felidae make sounds';
    }

    public function move(): void
    {
        echo 'Felidae are moving around';
    }
}
