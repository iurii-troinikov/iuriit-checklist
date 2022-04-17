<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private string $text;
    public function getId(): int
    {
        return $this->id;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
