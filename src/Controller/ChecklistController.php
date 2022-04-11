<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Enum\FlashMessagesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/checklist", name="checklist_")
 */
class ChecklistController extends AbstractController
{
    /**
     * @Route(name="add", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
       $name = $request->request->get('name');
       $checklist = new Checklist($name);

       /** @var ConstraintViolationList $errors */
       $errors = $validator->validate($checklist);
        foreach ($errors as $error) {
            $this->addFlash(FlashMessagesEnum::FAIL, $error->getMessage());
        }
        if (!$errors->count()) {
            $em->persist ($checklist);
            $em->flush();
            $this->addFlash(FlashMessagesEnum::SUCCESS, sprintf('Checklist %s was created', $name));
        }
        return $this->redirectToRoute('page_home');
    }
    /**
     * @Route("/delete/{checklistId}", name="delete", requirements={"checklistId"="\d+"})
     */
    public function delete(string $checklistId, EntityManagerInterface $em): Response
    {
        $checklist = $em->getRepository(Checklist::class)->find($checklistId);
        if (!$checklist) {
            throw new NotFoundHttpException('Checklist not found');
        }
        $em->remove($checklist);
        $em->flush();
        $this->addFlash( FlashMessagesEnum::SUCCESS, sprintf('Checklist %s was removed', $checklist->getTitle()));
        return $this->redirectToRoute('page_home');
    }
}
