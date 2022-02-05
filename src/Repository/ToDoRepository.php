<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Checklist;
use App\Entity\ToDo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method ToDo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDo[]    findAll()
 * @method ToDo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDo::class);
    }
    private function selectByUser(UserInterface $user): QueryBuilder
    {
        return $this->createQueryBuilder('to_do')
            ->select('to_do')
            ->join('to_do.users', 'user')
            ->where('user = :user')
            ->orderBy('to_do.id', 'DESC')
            ->setParameter(':user', $user);
    }
    public function findByUser(UserInterface $user): array
    {
        return $this->selectByUser($user)
            ->getQuery()
            ->getResult();
    }
    public function findByChecklistAndUser(Checklist $checklist, UserInterface $user): array
    {
        return $this->selectByUser($user)
            ->andWhere('to_do.checklist = :checklist')
            ->setParameter(':checklist', $checklist)
            ->getQuery()
            ->getResult();
    }
}
