<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Enum\FlashMessagesEnum;
use App\Form\TodoType;
use App\Service\ToDoService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todos", name="todo_")
 * @IsGranted("ROLE_USER")
 */
class ToDoController extends AbstractController
{
    /**
     * @Route(name="list_all")
     */
    public function listAll(EntityManagerInterface $em): Response
    {
        return $this->render('checklist/list.html.twig', [
            'todos' => $em->getRepository(ToDo::class)->findByUser($this->getUser())
        ]);
    }
    /**
     * @Route("/checklist/{id}", name="list_by_checklist", requirements={"checklistId"="\d+"})
     * @IsGranted("IS_OWNER", subject="checklist")
     */
    public function listByChecklist(Checklist $checklist, EntityManagerInterface $em): Response
    {
        $todos = $em->getRepository(ToDo::class)->findByChecklistAndUser($checklist, $this->getUser());
        return $this->render('checklist/list.html.twig', [
            'todos' => $todos
        ]);
    }
    /**
     * @Route("/{id}", name="get", requirements={"id"="\d+"})
     * @IsGranted("IS_SHARED", subject="todo")
     */
    public function getAction(ToDo $todo): Response
    {
        return $this->render('checklist/get.html.twig', [
            'todo' => $todo
        ]);
    }
    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function createAction(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $em->persist($todo);
            $em->flush();
            $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was successfully created', $todo->getText()));
            return $this->redirectToRoute('todo_list_all');
        }
            return $this->renderForm('checklist/create.html.twig', [
            'form' => $form,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("IS_SHARED", subject="todo")
     */
    public function deleteAction(ToDo $todo, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === $todo->getUser()) {
            $entityManager->remove($todo);
        } else {
            $todo->getUsers()->removeElement($this->getUser());
        }
        $entityManager->flush();
        $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was deleted', $todo->getText()));
        return $this->redirectToRoute('todo_list_all');
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, ToDo $todo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($todo);
            $em->flush();
            $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was successfully changed', $todo->getText()));
            return $this->redirectToRoute('todo_get', ['id' => $todo->getId()]);
        }
            return $this->renderForm('checklist/edit.html.twig', [
            'form' => $form,
            'todo' => $todo,
        ]);
    }
}
