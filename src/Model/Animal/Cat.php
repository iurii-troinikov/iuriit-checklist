<?php

declare(strict_types=1);

namespace App\Model\Animal;

class Cat extends Pet
{
    public function purrs(): void
    {
        echo 'Cat purrs';
    }
}
