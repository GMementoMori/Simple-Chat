<?php

namespace App\Repository;

use App\Entity\UserRelations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserRelations|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRelations|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRelations[]    findAll()
 * @method UserRelations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRelations::class);
    }

    public function findFriendsByUserId($userId)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
                    FROM App\Entity\User u 
                    INNER JOIN App\Entity\UserRelations ur
                    WHERE u.id = ur.friend_id 
                    AND ur.user_id = :user_id'
        )
            ->setParameters(['user_id' => $userId])
            ->getResult();
    }

}
