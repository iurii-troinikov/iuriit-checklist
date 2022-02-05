<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Activity\Activity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/activity", name="activity_")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/visit", name="visit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function visit(EntityManagerInterface $em): Response
    {
        return $this->render('activity/visit.html.twig', [
            'data' => $em->getRepository(Activity::class)->getVisitActivityData()
        ]);
    }

    /**
     * @Route("/visit-qb", name="visit-qb")
     * @IsGranted("ROLE_ADMIN")
     */
    public function visitQB(EntityManagerInterface $em, Request $request): Response
    {
        $itemsPerPage = 20;
        $page = (int) $request->get('page');
        $offset = ($page ? $page - 1 : 0) * $itemsPerPage;

        return $this->render('activity/visitQB.html.twig', [
            'activities' => $em->getRepository(Activity::class)->getVisitActivityDataQB(
                $itemsPerPage,
                $offset
            )
        ]);
    }

    /**
     * @Route("/todo", name="todo")
     * @IsGranted("ROLE_USER")
     */
    public function todo(EntityManagerInterface $em): Response
    {
        return $this->render('activity/todo.html.twig', [
            'data' => $em->getRepository(Activity::class)->getTodoActivityData($this->getUser())
        ]);
    }
}
