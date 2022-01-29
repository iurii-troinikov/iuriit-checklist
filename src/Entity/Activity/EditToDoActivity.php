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
    public function __construct(User $user, ToDo $toDo) {
        parent::__construct($user);
        $this->toDo = $toDo;
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
}
