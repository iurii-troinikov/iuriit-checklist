<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Checklist;
use App\Entity\ToDo;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TodoNormalizer implements ContextAwareDenormalizerInterface
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;
    private ObjectNormalizer $objectNormalizer;
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        ObjectNormalizer $objectNormalizer
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->objectNormalizer = $objectNormalizer;
    }
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === ToDo::class;
    }
    public function denormalize($data, string $type, string $format = null, array $context = []): ToDo
    {
        if ($context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? []) {
            return $this->updateTodo($context[AbstractNormalizer::OBJECT_TO_POPULATE], $data);
        }
        $text = $data['text'] ?? '';
        $checklist = $this->findChecklist($data['checklist']['id'] ?? null);
        return new ToDo($text, $checklist);
    }
    private function updateTodo($objectToPopulate, $data): ToDo
    {
        if (!$objectToPopulate instanceof ToDo) {
            throw new LogicException('TodoNormalizer can update only Todo entity');
        }
        $objectToPopulate = $this->objectNormalizer->denormalize($data, ToDo::class, null, [
            AbstractNormalizer::OBJECT_TO_POPULATE => $objectToPopulate,
            'groups' => ['API_UPDATE']
        ]);
        $checklistId = $data['checklist']['id'] ?? null;
        if (!$checklistId) {
            return $objectToPopulate;
        }
        $checklist = $this->findChecklist($checklistId);
        $objectToPopulate->setChecklist($checklist);
        return $objectToPopulate;
    }
    private function findChecklist(?int $checklistId): ?Checklist
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user instanceof UserInterface) {
            throw new LogicException('To create todo User should be authenticated');
        }

        $checklist = $checklistId
            ? $this->em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $user])
            : null;
        if (!$checklist) {
            throw new ValidationException('Missed checklist');
        }
        return $checklist;
    }
}
