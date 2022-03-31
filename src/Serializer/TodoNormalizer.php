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
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TodoNormalizer implements ContextAwareDenormalizerInterface
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === ToDo::class;
    }
    public function denormalize($data, string $type, string $format = null, array $context = []): ToDo
    {
        $text = $data['text'] ?? '';
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user instanceof UserInterface) {
            throw new LogicException('To create todo User should be authenticated');
        }
        $checklistId = $data['checklist']['id'] ?? null;
        /** @var Checklist|null $checklist */
        $checklist = $checklistId
            ? $this->em->getRepository(Checklist::class)->findOneBy(['id' => $checklistId, 'user' => $user])
            : null;
        if (!$checklist) {
            throw new ValidationException('Missed checklist');
        }
        return new ToDo($text, $checklist);
    }
}
