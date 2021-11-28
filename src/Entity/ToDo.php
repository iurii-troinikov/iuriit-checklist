<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ToDoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoRepository", repositoryClass=ToDoRepository::class)
 */

class ToDo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="ToDo text should not be blank")
     * @Assert\Length(
     *      min = 30,
     *      max = 254,
     *      minMessage = "ToDo text should be at least {{ limit }} characters long",
     *      maxMessage = "ToDo text cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=100)
     */
    private string $text;

    /**
     * @ORM\ManyToOne(targetEntity=Checklist::class, inversedBy="toDos")
     * @ORM\JoinColumn(nullable=false)
     */
    private Checklist $checklist;

    public function __construct(string $text, Checklist $checklist)
    {
        $this->text = $text;
        $this->checklist = $checklist;
    }


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

    public function getChecklist(): Checklist
    {
        return $this->checklist;
    }

    public function setChecklist(Checklist $checklist): self
    {
        $this->checklist = $checklist;

        return $this;
    }
}
