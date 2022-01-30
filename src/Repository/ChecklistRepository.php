<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Checklist;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Checklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Checklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Checklist[]    findAll()
 * @method Checklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checklist::class);
    }
    public function selectByUser(UserInterface $user): QueryBuilder
    {
        return $this->createQueryBuilder('checklist')
            ->where('checklist.user = :user')
            ->setParameter(':user', $user);
    }
}
