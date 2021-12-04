<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checklist;
use App\Enum\FlashMessagesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/checklist", name="checklist_")
 * @IsGranted("ROLE_USER")
 */
class ChecklistController extends AbstractController
{
    /**
     * @Route(name="add", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
       $name = $request->request->get('name');
       $checklist = new Checklist($name, $this->getUser());
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

