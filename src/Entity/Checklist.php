<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChecklistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChecklistRepository", repositoryClass=ChecklistRepository::class)
 */
class Checklist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
