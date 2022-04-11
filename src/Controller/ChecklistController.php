<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\Todo;
use App\Repository\todoRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 */
class ChecklistController extends AbstractController
{
    private todoRepository $todoRepository;

    public function __construct(todoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    private array $checklists = [
        1 => [
            'title' => 'My summer weekends',
            'todos' => [1, 2, 3]
        ],
        2 => [
            'title' => 'My favorite books review',
            'todos' => [4, 5, 6]
        ],
        3 => [
            'title' => 'My friends hobbies',
            'todos' => [7, 8, 9]
        ]

    ];

    private array $todos = [
        1 => [
            'id' => 1,
            'text' => 'Loren ipsun 1',
            'checklist_id' => 1
        ],
        2 => [
            'id' => 2,
            'text' => 'Loren ipsun 2',
            'checklist_id' => 1
        ],
        3 => [
            'id' => 3,
            'text' => 'Loren ipsun 3',
            'checklist_id' => 1

        ],
        4 => [
            'id' => 4,
            'text' => 'Loren ipsun 4',
            'checklist_id' => 2

        ],
        5 => [
            'id' => 5,
            'text' => 'Loren ipsun 5',
            'checklist_id' => 2

        ],
        6 => [
            'id' => 6,
            'text' => 'Loren ipsun 6',
            'checklist_id' => 2

        ],
        7 => [
            'id' => 7,
            'text' => 'Loren ipsun 7',
            'checklist_id' => 3

        ],
        8 => [
            'id' => 8,
            'text' => 'Loren ipsun 8',
            'checklist_id' => 3

        ],
        9 => [
            'id' => 9,
            'text' => 'Loren ipsun 9',
            'checklist_id' => 3

        ]

    ];


    /**
     * @Route(name="list_all")
     */
    public function listAll(): Response
    {

        return $this->render('checklist/list.html.twig', [
            'todos' => $this->todos,
        ]);
    }

    /**
     * @Route("/{checklistId}", name="list_by_checklist", requirements={"checklistId"="\d+"})
     */
    public function listBychecklist(string $checklistId): Response
    {
        if (!isset($this->checklists[(int)$checklistId])) {
            throw new Exception('You ask for checklist that not exists');
        }

        $checklist = $this->checklists[(int)$checklistId] ?? null;
        $todosIds = $checklist['todos'];

        $todos = array_filter($this->todos, function (array $todo) use ($todosIds) {
            return in_array($todo['id'], $todosIds, true);
        });

        return $this->render('checklist/list.html.twig', [
            'todos' => $todos
        ]);
    }

    /**
     * @Route("/{checklistId}/{todoId}", name="get", requirements={"checklistId"="\d+", "todoId"="\d+"})
     */
    public function getAction(string $checklistId, string $todoId): Response
    {

        if (!isset($this->checklists[(int)$checklistId])) {
            throw new Exception('You ask for checklist that not exists');
        }

        $checklist = $this->checklists[(int)$checklistId] ?? null;
        $todosIds = $checklist['todos'];

        $todos = array_filter($this->todos, function (array $todo) use ($todosIds) {
            return in_array($todo['id'], $todosIds, true);
        });
        if (!isset($todos[(int)$todoId])) {
            throw new Exception('There is no todo in selected checklist');
        }


        $todo = $todos[(int)$todoId];

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
        $newtodo = new Todo();
        $newtodo
            ->setTitle('New todo title')
            ->setText('New todo text');

        $entityManager->persist($newtodo);
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
    public function createtodoAction(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todoToCreate = $this->todoRepository->find($id);
        $entityManager->persist($todoToCreate);
        $entityManager->flush();
        return $this->render('checklist/create_todo.html.twig', [
            'id' => $id,
        ]);
    }
    /**
     * @Route("/create_checklist/{id}", name="create_checklist")
     */
    public function createchecklistAction(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $checklistToCreate = $this->checklistRepository->find($id);
        $entityManager->persist($checklistToCreate);
        $entityManager->flush();

        return $this->render('checklist/create_checklist.html.twig', [
            'id' => $id,
        ]);
    }
}


