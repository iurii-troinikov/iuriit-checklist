<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route(name="page_home")
     */
    public function index(): Response
    {

       return self::$checklists->redirectToRoute('checklist_list_all');
    }
}
