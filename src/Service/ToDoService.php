<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Enum\FlashMessagesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ToDoService
{
    private ValidatorInterface $validator;
    private Session $session;
    private EntityManagerInterface $em;

    public function __construct(
        ValidatorInterface $validator,
        SessionInterface $session,
        EntityManagerInterface $em
    ){
        $this->validator = $validator;
        $this->session = $session;
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
        $this->session->getFlashBag()->add(FlashMessagesEnum::FAIL, $error->getMessage());
    }
    if (!$errors->count()) {
        $this->em->persist($todo);
        $this->em->flush();
        $this->session->getFlashBag()->add(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was created', $todo->getText()));
    }
}
}