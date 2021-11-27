<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 */

class ToDoController extends AbstractController
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
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function createAction(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->getMethod() === 'GET') {

            $checklists = $em->getRepository(Checklist::class)->findAll();

            return $this->render('checklist/create.html.twig', [
                'checklists' => $checklists
            ]);
        }

        $text = (string) $request->request->get('text');
        $checklistId = (int) $request->request->get('checklist_id');
        $checklist = $em->getRepository(Checklist::class)->find($checklistId);

        if (!$checklist) {
            throw new NotFoundHttpException('Checklist not found');
        }

         $todo = new ToDo($text, $checklist);

        $em->persist($todo);
        $em->flush();

        $this->addFlash('success', sprintf('Todo "%s" was created', $todo->getText()));

        return $this->redirectToRoute('checklist_create');

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function deleteAction(int $id, EntityManagerInterface $entityManager): Response
    {
        $todoToDelete = $this->todoRepository->find($id);
        if (!$todoToDelete) {
            throw new NotFoundHttpException('Todo not found');
        }

        $entityManager->remove($todoToDelete);
        $entityManager->flush();
        $this->addFlash('success', sprintf('Todo "%s" was deleted', $todoToDelete->getText()));
        return $this->redirectToRoute('checklist_list_all');
    }

}


