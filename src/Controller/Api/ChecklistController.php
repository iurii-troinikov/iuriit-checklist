<?php

declare(strict_types=1);

namespace App\Controller\Api;

use  App\Entity\Checklist;
use App\Model\API\ApiResponse;
use App\Service\ChecklistService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklist", name="checklist_")
 * @IsGranted("ROLE_USER")
 */
class ChecklistController extends AbstractApiController
{
    /**
     * @Route(name="add", methods={"POST"})
     */
    public function create(Request $request, ChecklistService $checklistService): ApiResponse
    {
        $requestContent = $this->serializer->decode($request->getContent(), 'json');
        $checklistName = $requestContent['name'] ?? null;
        $checklist = $checklistService->createAndFlush($checklistName);
        return new ApiResponse($this->serializer->serialize($checklist, 'json', [
            'groups' => ['API_GET'],
        ]));
    }
    /**
     * @Route(name="get", methods={"GET"})
     */
    public function getAction(EntityManagerInterface $em): Response
    {
        $checklists = $em->getRepository(Checklist::class)->findBy([
            'user' => $this->getUser()
        ]);
        return new ApiResponse($this->serializer->serialize($checklists, 'json', [
            'groups' => ['API_GET']
        ]));
    }
    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     *
     * @IsGranted("IS_OWNER", subject="checklist", statusCode=404)
     */
    public function delete(Checklist $checklist, EntityManagerInterface $em): Response
    {
        $em->remove($checklist);
        $em->flush();
        return new ApiResponse();
    }
}
