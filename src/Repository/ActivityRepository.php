<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Activity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    /**
     * @return QueryBuilder
     */
    public function selectVisitActivityData(): QueryBuilder
    {
        return $this->createQueryBuilder('activity')
            ->orderBy('activity.createdAt', 'DESC')
            ->where('activity INSTANCE OF App\Entity\Activity\VisitActivity');
    }

    /**
     * @param UserInterface $user
     * @return QueryBuilder
     */
    public function selectTodoActivityData(UserInterface $user): QueryBuilder
    {
        return $this->createQueryBuilder('activity')
            ->orderBy('activity.createdAt', 'DESC')
            ->where('activity.user = :user')
            ->andWhere('activity INSTANCE OF App\Entity\Activity\EditToDoActivity')
            ->setParameter('user', $user);
    }

    public function getTodoActivityData(UserInterface $user): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $stmt = $connection->prepare('
            SELECT 
            activity.created_at AS created_at,
            to_do.text AS todo_name
                   
            FROM activity
            
            JOIN to_do ON to_do.id = activity.to_do_id
            
            WHERE type = :type
            AND activity.user_id = :user
            ORDER BY created_at DESC
        ');
        $result = $stmt->executeQuery([
            'type' => 'edit_todo',
            'user' => $user->getId()
        ]);

        return $result->fetchAllAssociative();
    }
}
