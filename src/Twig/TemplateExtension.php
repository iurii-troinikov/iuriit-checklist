<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Checklist;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TemplateExtension extends AbstractExtension
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_checklists', [$this, 'getChecklists']),
        ];
    }
    public function getChecklists(): array
    {
return  $this->em->getRepository(Checklist::class)->findAll();
    }
}

