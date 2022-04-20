<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\Ownable;
use App\Repository\ToDoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoRepository", repositoryClass=ToDoRepository::class)
 */
class ToDo implements Ownable
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
     * @ORM\Column(type="string", length=250)
     */
    private string $text;
    /**
     * @ORM\ManyToOne(targetEntity=Checklist::class, inversedBy="toDos")
     * @ORM\JoinColumn(nullable=false)
     */
    private Checklist $checklist;
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $user;
    public function __construct(string $text, Checklist $checklist, UserInterface $user)
    {
        $this->text = $text;
        $this->checklist = $checklist;
        $this->user = $user;

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
    public function getUser(): UserInterface
    {
        return $this->user;
    }
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }
}
