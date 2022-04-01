<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\ToDo;
use App\Exception\ValidationException;
use App\Model\API\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/todo", name="todo_")
 */
class ToDoController extends AbstractApiController
{
    /**
     * @Route(name="create", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        /** @var ToDo $todo */
        $todo = $this->serializer->deserialize($request->getContent(), ToDo::class, 'json');
        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($todo);
        if ($errors->count()) {
            throw new ValidationException('', $errors);
        }
        $todo->setOwner($this->getUser());
        $em->persist($todo);
        $em->flush();
        return new ApiResponse($this->serializer->serialize($todo, 'json', [
            'groups' => ['API'],
        ]));
    }
    /**
     * @Route(name="list", methods={"GET"})
     */
    public function list(EntityManagerInterface $em): Response
    {
        return new ApiResponse($this->serializer->serialize(
            $em->getRepository(ToDo::class)->selectByUser($this->getUser())->getQuery()->getResult(),
            'json',
            ['groups' => 'API']
        ));
    }
}
