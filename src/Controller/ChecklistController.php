<?php
declare(strict_types=1);

namespace App\Controller;
use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use App\Repository\ChecklistRepository;
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
            'text' => 'Loren ipsum 1',
            'checklist_id' => 1,
            'done' => true
        ],
        2 => [
            'id' => 2,
            'text' => 'Loren ipsum 2',
            'checklist_id' => 1,
            'done' => true
        ],
        3 => [
            'id' => 3,
            'text' => 'Loren ipsum3',
            'checklist_id' => 1,
            'done' => true

        ],
        4 => [
            'id' => 4,
            'text' => 'Loren ipsum 4',
            'checklist_id' => 2,
            'done' => true

        ],
        5 => [
            'id' => 5,
            'text' => 'Loren ipsum 5',
            'checklist_id' => 2,
            'done' => false

        ],
        6 => [
            'id' => 6,
            'text' => 'Loren ipsum 6',
            'checklist_id' => 2,
            'done' => false
        ],
        7 => [
            'id' => 7,
            'text' => 'Loren ipsum 7',
            'checklist_id' => 3,
            'done' => false

        ],
        8 => [
            'id' => 8,
            'text' => 'Loren ipsum 8',
            'checklist_id' => 3,
            'done' => false

        ],
        9 => [
            'id' => 9,
            'text' => 'Loren ipsum 9',
            'checklist_id' => 3,
            'done' => true

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
    public function listByChecklist(string $checklistId): Response
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


