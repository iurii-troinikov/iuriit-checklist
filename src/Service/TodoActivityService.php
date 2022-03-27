<?php

namespace App\Service;

use App\Entity\Activity\EditToDoActivity;
use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
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
    public function createEditTodoActivity(ToDo $todo, array $changes)
    {
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;

        if (!$user instanceof User) {
            throw new ValidationException('User not exists in request');
        }
        $activity = new EditTodoActivity($user, $todo, $this->prepareChanges($changes));
        $this->em->persist($activity);
        $this->em->flush();
    }
    private function prepareChanges(array $changes): array
    {
        $result = [];
        foreach ($changes as $key => $itemChanges) {
            if ($key === 'checklist') {
                $result[$key] = $this->prepareChecklist($itemChanges);
                continue;
            }
            $result[$key] = $itemChanges;
        }
        return $result;
    }
    /**
     * @param Checklist[] $checklists
     * @return array
     */
    private function prepareChecklist(array $checklists): array
    {
        $result = [];
        foreach ($checklists as $checklist) {
            $result[] = $checklist->getId();
        }
        return $result;
    }
}
