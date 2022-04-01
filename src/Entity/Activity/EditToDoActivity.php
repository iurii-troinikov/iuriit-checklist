<?php

declare(strict_types=1);

namespace App\Entity\Activity;

use App\Entity\User;
use App\Entity\ToDo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EditToDoActivity extends Activity
{
    /**
     * @ORM\ManyToOne(targetEntity=ToDo::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private ToDo $toDo;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $changes = [];
    public function __construct(User $user, ToDo $toDo, array $changes) {
        parent::__construct($user);
        $this->toDo = $toDo;
        $this->changes = $changes;
    }
    public function getToDo(): ToDo
    {
        return $this->toDo;
    }
    public function setToDo(ToDo $toDo): self
    {
        $this->toDo = $toDo;

        return $this;
    }

    public function getChanges(): ?array
    {
        return $this->changes;
    }

    public function setChanges(?array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }
}
