<?php

declare(strict_types=1);

namespace App\Model\Animal;

class Dog extends Pet
{
    public function barks(): void
    {
        echo 'Dog barks';
    }
}
