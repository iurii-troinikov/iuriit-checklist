<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Checklist;
use App\Entity\ToDo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
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
    public function createAndFlush(string $text, int $checklistId, UserInterface $user): void
{
    $checklist = $this->em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $user]);
    if (!$checklist) {
        throw new NotFoundHttpException('Checklist not found');
    }
    $todo = new ToDo($text, $checklist, $user);
    /** @var ConstraintViolationList $errors */
    $errors = $this->validator->validate($todo);
    foreach ($errors as $error) {
        throw new HttpException(400, $error->getMessage());
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
            throw new HttpException(400, $error->getMessage());
        }
        $this->em->persist($toDo);
        $this->em->flush();
    }
}
