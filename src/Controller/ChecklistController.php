<?php

declare(strict_types=1);

namespace App\Controller;

use  App\Entity\Checklist;
use App\Enum\FlashMessagesEnum;
use App\Form\ChecklistType;
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
        $checklistName = (string)$request->request->get('name');
        $checklistService->createAndFlush($checklistName);
        $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Checklist %s was created', $checklistName));

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
        $this->addFlash( FlashMessagesEnum::SUCCESS, sprintf('Checklist %s was removed', $checklist->getTitle()));
        return $this->redirectToRoute('page_home');
    }

        // Добавление нового чеклиста через форму https://iiuriit-checklist.local/checklist

    /**
     * @Route(name="new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, EntityManagerInterface $em): Response
    {

        $checklist = new Checklist('');

        $form = $this->createForm(ChecklistType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $checklist = $form->getData();
            $em->persist($checklist);
            $em->flush();
            $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Checklist "%s" was successfully created', $checklist->getTitle()));

            return $this->redirectToRoute('page_home');
        }

        return $this->renderForm('checklist/new.html.twig',
        [
            'form' => $form,
        ]);
    }
}
