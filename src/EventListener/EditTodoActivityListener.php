<?php

namespace App\EventListener;

use App\Entity\ToDo;
use App\Service\TodoActivityService;

class EditTodoActivityListener
{
    private TodoActivityService $todoActivityService;

    public function __construct(TodoActivityService $todoActivityService)
    {
        $this->todoActivityService = $todoActivityService;
    }

    public function postUpdate(ToDo $todo): void
    {
        $this->todoActivityService->createTodoEditActivity($todo);
    }
}
