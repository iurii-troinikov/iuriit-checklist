<?php

namespace App\EventListener;

use App\Entity\ToDo;
use App\Service\TodoActivityService;
use Doctrine\ORM\EntityManagerInterface;

class EditTodoActivityListener
{
    private TodoActivityService $todoActivityService;
    private EntityManagerInterface $em;

    public function __construct(TodoActivityService $todoActivityService, EntityManagerInterface $em)
    {
        $this->todoActivityService = $todoActivityService;
        $this->em = $em;
    }

    public function postUpdate(ToDo $todo): void
    {
        $uow =  $this->em->getUnitOfWork();
        $changes = $uow->getEntityChangeSet($todo);
        $this->todoActivityService->createTodoEditActivity($todo, $changes);
    }
}
