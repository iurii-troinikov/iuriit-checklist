<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ChecklistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    /**
     * @ORM\OneToMany(targetEntity=ToDo::class, mappedBy="checklist", orphanRemoval=true)
     */
    private Collection $toDos;
    public function __construct(string $title)
    {
        $this->title = $title;
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
}
