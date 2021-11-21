<?php
declare(strict_types=1);

namespace App\Controller;
use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 */

class ChecklistController extends AbstractController
{

    private ToDoRepository $todoRepository;

    public function __construct(ToDoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }


    /**
     * @Route(name="list_all")
     */
    public function listAll(EntityManagerInterface $em): Response
    {

        return $this->render('checklist/list.html.twig', [
            'todos' => $em->getRepository(ToDo::class)->findAll()
        ]);
    }

    /**
     * @Route("/{checklistId}", name="list_by_checklist", requirements={"checklistId"="\d+"})
     */
    public function listByChecklist(string $checklistId, EntityManagerInterface $em): Response
    {

        $todos = $em->getRepository(ToDo::class)->findBy([
            'checklist' => (int) $checklistId
        ]);

        return $this->render('checklist/list.html.twig', [
            'todos' => $todos
        ]);
    }

    /**
     * @Route("/{checklistId}/{todoId}", name="get", requirements={"checklistId"="\d+", "todoId"="\d+"})
     */
    public function getAction(string $checklistId, string $todoId, EntityManagerInterface $em): Response
    {
        $todo = $em->getRepository(ToDo::class)->findOneBy([
            'checklist' => (int) $checklistId,
            'id' => $todoId
        ]);

        return $this->render('checklist/get.html.twig', [
            'todo' => $todo
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $newToDo = new ToDo();
        $newToDo

            ->setText('New todo text');

        $entityManager->persist($newToDo);
        $entityManager->flush();


        $todos = $this->todoRepository->findAll();


        return $this->render('checklist/list.html.twig', [
            'todos' => $todos,
        ]);
    }

    /**
     * @Route("/delete_todo/{id}", name="delete_todo")
     */

    public function deleteAction(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todoToDelete = $this->todoRepository->find($id);
        $entityManager->remove($todoToDelete);
        $entityManager->flush();

        return $this->render('checklist/delete_todo.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/create_todo/{id}", name="create_todo")
     */

    public function createToDoAction(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $newToDo = new ToDo();
        $newToDo

            ->setText('New todo text');

        $entityManager->persist($newToDo);
        $entityManager->flush();


        $todos = $this->todoRepository->findAll();


        return $this->render('checklist/create_todo/list.html.twig', [
            'todos' => $newToDo,
        ]);
    }


    /**
     * @Route("/create_checklist/{id}", name="create_checklist")
     */

    public function createChecklistAction(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $newChecklist = new Checklist();
        $newChecklist
            ->setTitle('New Checklist title');


        $entityManager->persist($newChecklist);
        $entityManager->flush();


        $checklists = $this->ChecklistRepository->findAll();


        return $this->render('checklist/create_checklist/list.html.twig', [
            'checklists' => $newChecklist,
        ]);
    }
}


