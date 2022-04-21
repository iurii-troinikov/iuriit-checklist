<?php

namespace App\Repository;

use App\Entity\Activity\VisitActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VisitActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method VisitActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method VisitActivity[]    findAll()
 * @method VisitActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VisitActivity::class);
    }
}
