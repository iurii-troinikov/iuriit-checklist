<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Checklist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateExtension extends AbstractExtension
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_checklists', [$this, 'getChecklists']),
        ];
    }
    public function getChecklists(): array
    {
        $token = $this->tokenStorage->getToken();
        return $token
           ? $this->em->getRepository(Checklist::class)->findBy(['user' => $token->getUser()])
            : [];
    }
}
