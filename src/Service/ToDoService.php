<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ToDoService
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $em;
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ){
        $this->validator = $validator;
        $this->em = $em;
    }
    public function createAndFlush(string $text, int $checklistId): void
{
    $checklist = $this->em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $user]);
    if (!$checklist) {
        throw new NotFoundHttpException('Checklist not found');
    }
    $todo = new ToDo($text, $checklist);
    /** @var ConstraintViolationList $errors */
    $errors = $this->validator->validate($todo);
    foreach ($errors as $error) {
        throw new ValidationException($error->getMessage());
    }
        $this->em->persist($todo);
        $this->em->flush();
    }
    public function editAndFlush(ToDo $toDo, string $title, string $text, int $checklistId): void
    {
        $checklist = $this->em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $toDo->getUser()]);
        if (!$checklist) {
            throw new NotFoundHttpException('Checklist not found');
        }
        $toDo->setText($text)
            ->setChecklist($checklist);
        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($toDo);
        foreach ($errors as $error) {
            throw new ValidationException($error->getMessage());
        }
        $this->em->persist($toDo);
        $this->em->flush();
    }
}
