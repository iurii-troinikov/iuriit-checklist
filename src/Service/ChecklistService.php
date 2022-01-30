<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Checklist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChecklistService
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
    public function createAndFlush(string $name): void
{
    $checklist = new Checklist($name);
    /** @var ConstraintViolationList $errors */
    $errors = $this->validator->validate($checklist);
    foreach ($errors as $error) {
        throw new HttpException(400, $error->getMessage());
    }
        $this->em->persist ($checklist);
        $this->em->flush();
}
}
