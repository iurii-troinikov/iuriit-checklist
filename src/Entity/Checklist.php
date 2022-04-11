<?php

namespace App\Entity;

use App\Repository\checklistRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=checklistRepository::class)
 */
class checklist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $todos;


    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function gettodos(): string
    {
        return $this->todos;
    }

    public function settodos(string $todos): self
    {
        $this->todos = $todos;

        return $this;
    }
}

