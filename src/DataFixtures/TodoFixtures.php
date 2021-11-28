<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Checklist;
use App\Entity\ToDo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TodoFixtures extends Fixture
{
    private $checklistTitles = [
        'My summer weekends',
        'My favorite books review',
        'My friends hobbies',
    ];
    public function load(ObjectManager $manager): void
    {
        $checklists = [];
        for ($i = 0; $i < 3; $i++) {
            $checklist = new Checklist($this->checklistTitles[$i]);
            $manager->persist($checklist);
            $checklists[] = $checklist;
        }
        for ($i = 0; $i <= 9; $i++) {
            $todo = new ToDo(
                'Loren ipsum' . $i,
                $checklists[random_int(0, 2)]
            );
            $manager->persist($todo);
        }
        $manager->flush();
    }
}
