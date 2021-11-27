<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 */

class ChecklistController extends AbstractController
{

    /**
     * @Route(name="add", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
       $name = $request->request->get('name');
       $em->persist (new Checklist($name));
       $em->flush();

       $this->addFlash('success', sprintf('Checklist %s was created', $name));

       return $this->redirectToRoute('page_home');
    }

    /**
     * @Route("/{checklistId}", name="delete", requirements={"checklistId"="\d+"})
     */
    public function delete(string $checklistId, EntityManagerInterface $em): Response
    {
        $checklist = $em->getRepository(Checklist::class)->find($checklistId);
        if (!$checklist) {
            throw new NotFoundHttpException('Checklist not found');
        }

        $em->remove($checklist);
        $em->flush();

        $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Category %s was removed', $checklist->getTitle()));

        return $this->redirectToRoute('page_home');
    }

}

