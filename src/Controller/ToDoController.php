<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Enum\FlashMessagesEnum;
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
     * @IsGranted("IS_OWNER", subject="todo")
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
    public function createAction(Request $request, EntityManagerInterface $em, ToDoService $toDoService): Response
    {
        if ($request->getMethod() === 'GET') {
            $checklists = $em->getRepository(Checklist::class)->findBy(['user' => $this->getUser()]);
            return $this->render('checklist/create.html.twig', [
                'checklists' => $checklists
            ]);
        }
        $toDoService->createAndFlush(
            (string) $request->request->get('text'),
            (int) $request->request->get('checklist_id'),
            $this->getUser()
                );

        return $this->redirectToRoute('todo_create');
    }
    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("IS_OWNER", subject="todo")
     */
    public function deleteAction(ToDo $todo, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($todo);
        $entityManager->flush();
        $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was deleted', $todo->getText()));
        return $this->redirectToRoute('todo_list_all');
    }

    /**
     * @Route("/edit{id}", name="edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, ToDo $toDo, EntityManagerInterface $em, ToDoService $toDoService): Response
    {
        if ($request->getMethod() === 'GET') {
            $checklists = $em->getRepository(Checklist::class)->findBy(['user' => $this->getUser()]);
            return $this->render('checklist/edit.html.twig', [
                'todo' => $toDo,
                'checklists' => $checklists
            ]);
        }
        $title = (string) $request->request->get('title');
        $toDoService->editAndFlush(
            $toDo,
            $title,
            (string) $request->request->get('text'),
            (int) $request->request->get('checklist_id')
        );
        $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was edited', $title));

        return $this->redirectToRoute('todo_get', ['id' => $toDo->getId()]);
    }
}
