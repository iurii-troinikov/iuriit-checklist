<?php

namespace App\Repository;

use App\Entity\checklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method checklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method checklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method checklist[]    findAll()
 * @method checklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class checklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, checklist::class);
    }

    // /**
    //  * @return checklist[] Returns an array of checklist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?checklist
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
