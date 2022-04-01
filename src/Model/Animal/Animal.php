<?php

declare(strict_types=1);

namespace App\Model\Animal;

interface Animal
    {
        public function voice(): void;
        public function move(): void;
    }
