<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Enum\FlashMessagesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            'todos' => $em->getRepository(ToDo::class)->findBy(['user' => $this->getUser()])
        ]);
    }
    /**
     * @Route("/checklist/{id}", name="list_by_checklist", requirements={"checklistId"="\d+"})
     * @IsGranted("IS_OWNER", subject="checklist")
     */
    public function listByChecklist(Checklist $checklist, EntityManagerInterface $em): Response
    {
        $todos = $em->getRepository(ToDo::class)->findBy([
            'checklist' => $checklist,
            'user' => $this->getUser()
        ]);
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
    public function createAction(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        if ($request->getMethod() === 'GET') {
            $checklists = $em->getRepository(Checklist::class)->findBy(['user' => $this->getUser()]);
            return $this->render('checklist/create.html.twig', [
                'checklists' => $checklists
            ]);
        }
        $text = (string) $request->request->get('text');
        $checklistId = (int) $request->request->get('checklist_id');
        $checklist = $em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $this->getUser()]);
        if (!$checklist) {
            throw new NotFoundHttpException('Checklist not found');
        }
        $todo = new ToDo($text, $checklist, $this->getUser());
        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($todo);
        foreach ($errors as $error) {
            $this->addFlash(FlashMessagesEnum::FAIL, $error->getMessage());
        }
        if (!$errors->count()) {
            $em->persist($todo);
            $em->flush();
            $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Todo "%s" was created', $todo->getText()));
        }
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
}


