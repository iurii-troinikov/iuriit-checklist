<?php
declare(strict_types=1);
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 */

class ChecklistController extends AbstractController
{
    private array $categories = [
       1 => [
          'title' => 'My summer weekends',
           'notes' => [1, 2, 3]
       ],
        2 => [
            'title' => 'My favorite books review',
             'notes' => [4, 5, 6]
        ],
        3 => [
            'title' => 'My friends hobbies',
             'notes' => [7, 8, 9]
        ]

    ];

    private array $notes = [
        1 => [
            'id' => 1,
            'title' => 'Some note 1',
            'text' => 'Loren ipsun 1',
            'category_id' => 1
        ],
     2 => [
         'id' => 2,
            'title' => 'Some note 2',
            'text' => 'Loren ipsun 2',
         'category_id' => 1
     ],
     3 => [
         'id' => 3,
            'title' => 'Some note 3',
            'text' => 'Loren ipsun 3',
         'category_id' => 1

        ],
     4 => [
         'id' => 4,
            'title' => 'Some note 4',
            'text' => 'Loren ipsun 4',
         'category_id' => 2

        ],
     5 => [
         'id' => 5,
            'title' => 'Some note 5',
            'text' => 'Loren ipsun 5',
         'category_id' => 2

        ],
     6 => [
         'id' => 6,
            'title' => 'Some note 6',
            'text' => 'Loren ipsun 6',
         'category_id' => 2

        ],
     7 => [
         'id' => 7,
            'title' => 'Some note 7',
            'text' => 'Loren ipsun 7',
         'category_id' => 3

        ],
     8 => [
         'id' => 8,
            'title' => 'Some note 8',
            'text' => 'Loren ipsun 8',
         'category_id' => 3

        ],
     9 => [
         'id' => 9,
            'title' => 'Some note 9',
            'text' => 'Loren ipsun 9',
         'category_id' => 3

        ]

    ];


    /**
     * @Route(name="list_all")
     */
    public function listAll(): Response
    {

      return $this->render('checklist/list.html.twig', [
          'notes' => $this->notes,
        ]);
    }

    /**
     * @Route("/{categoryId}", name="list_by_category", requirements={"categoryId"="\d+"})
     */
    public function listByCategory(string $categoryId): Response
    {
        if (!isset($this->categories[(int) $categoryId])) {
            throw new Exception('You ask for category that not exists');
        }

        $category = $this->categories[(int) $categoryId] ?? null;
        $notesIds = $category['notes'];

        $notes = array_filter($this->notes, function (array $note) use ($notesIds) {
            return in_array($note['id'], $notesIds, true);
        });

           return $this->render('checklist/list.html.twig', [
            'notes' => $notes
        ]);
    }

    /**
     * @Route("/{categoryId}/{noteId}", name="get", requirements={"categoryId"="\d+", "noteId"="\d+"})
     */
    public function getAction(string $categoryId, string $noteId): Response
    {

        if (!isset($this->categories[(int)$categoryId])) {
            throw new Exception('You ask for category that not exists');
        }

        $category = $this->categories[(int)$categoryId] ?? null;
        $notesIds = $category['notes'];

        $notes = array_filter($this->notes, function (array $note) use ($notesIds) {
            return in_array($note['id'], $notesIds, true) ;
        });
        if (!isset($notes[(int)$noteId])) {
            throw new Exception('There is no note in selected category');
        }


        $note = $notes[(int) $noteId];

        return $this->render('checklist/get.html.twig', [
            'notes' => $note
        ]);
    }
}

