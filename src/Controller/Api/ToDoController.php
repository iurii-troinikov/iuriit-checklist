<?php

declare(strict_types=1);

namespace App\Controller\Api;
use App\Model\API\ApiResponse;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo", name="todo_")
 */
class ToDoController extends AbstractApiController
{
    /**
     * @Route(name="create", methods={"POST"})
     *
     * @IsGranted("IS_ANONYMOUS_USER")
     */
    public function create(Request $request, UserService $userService): Response {

    }
}
