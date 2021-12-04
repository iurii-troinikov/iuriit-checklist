<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChecklistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    private ?int $id = null;
    /**
     * @Assert\NotBlank(message="Checklist name should not be blank")
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Checklist name should be at least {{ limit }} characters long",
     *      maxMessage = "Checklist name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private string $title;
    /**
     * @ORM\OneToMany(targetEntity=ToDo::class, mappedBy="checklist", orphanRemoval=true)
     */
    private Collection $toDos;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;
    public function __construct(string $title, User $user)
    {
        $this->title = $title;
        $this->user = $user;
        $this->toDos = new ArrayCollection();
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
    /**
     * @return Collection|ToDo[]
     */
    public function getToDos(): Collection
    {
        return $this->toDos;
    }
    public function addToDo(ToDo $toDo): self
    {
        if (!$this->toDos->contains($toDo)) {
            $this->toDos[] = $toDo;
            $toDo->setChecklist($this);
        }
        return $this;
    }
    public function removeToDo(ToDo $toDo): self
    {
        if ($this->toDos->removeElement($toDo)) {

            if ($toDo->getChecklist() === $this) {
                $toDo->setChecklist(null);
            }
        }
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
