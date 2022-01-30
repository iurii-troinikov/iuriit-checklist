<?php

namespace App\Service;

use App\Entity\Activity\EditToDoActivity;
use App\Entity\ToDo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TodoActivityService
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function createTodoEditActivity(ToDo $todo)
    {
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;

        if (!$user instanceof User) {
            throw new HttpException(400, 'User not exists in request');
        }

        $activity = new EditTodoActivity($user, $todo);

        $this->em->persist($activity);
        $this->em->flush();
    }
}
