<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TodoFixtures extends Fixture
{
    private $checklistTitles = [
        'My summer weekends',
        'My favorite books review',
        'My friends hobbies',
    ];

    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i = 0; $i < 3; $i++) {

            $user = $this->userService->create("User$i", "user $i");
            $manager->persist($user);
            $users[] = $user;
        }
        $checklists = [];
        for ($i = 0; $i < 3; $i++) {
            $checklist = new Checklist($this->checklistTitles[$i], $users[$i]);
            $manager->persist($checklist);
            $checklists[] = $checklist;
        }
        for ($i = 0; $i <= 9; $i++) {
            $checklist = $checklists[random_int(0, 2)];
            $todo = new ToDo(
                'Loren ipsum' . $i,
                $checklist,
                $checklist->getUser()
            );
            $manager->persist($todo);
        }
        $manager->flush();
    }
}

