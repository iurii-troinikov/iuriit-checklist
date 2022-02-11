<?php

namespace App\EventListener;

use App\Entity\ToDo;
use App\Service\TodoActivityService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EditTodoActivitySubscriber implements EventSubscriberInterface
{
    private TodoActivityService $todoActivityService;

    public function __construct(TodoActivityService $todoActivityService)
    {
        $this->todoActivityService = $todoActivityService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof ToDo) {
            return;
        }

        $this->todoActivityService->createTodoEditActivity($entity);
    }
}
