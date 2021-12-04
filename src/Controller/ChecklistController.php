<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Enum\FlashMessagesEnum;
use App\Service\ChecklistService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 * @IsGranted("ROLE_USER")
 */
class ChecklistController extends AbstractController
{
    /**
     * @Route(name="add", methods={"POST"})
     */
    public function create(Request $request, ChecklistService $checklistService): Response
    {
        $checklistService->createAndFlush((string)$request->request->get('name'), $this->getUser());
        return $this->redirectToRoute('page_home');
    }
    /**
     * @Route("/{id}", name="delete", requirements={"checklistId"="\d+"})
     * @IsGranted("IS_OWNER", subject="checklist")
     */
    public function delete(Checklist $checklist, EntityManagerInterface $em): Response
    {
        $em->remove($checklist);
        $em->flush();
        $this->addFlash( FlashMessagesEnum::SUCCESS, sprintf('Category %s was removed', $checklist->getTitle()));
        return $this->redirectToRoute('page_home');
    }
}

