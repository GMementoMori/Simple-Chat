<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserRelations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmailSimular($email)
    {
        $result = $this->createQueryBuilder('u')
            ->select('u.email')
            ->distinct()
            ->andWhere('u.email LIKE :email')
            ->setParameters(['email' => '%' . $email . '%'])
            ->getQuery();
        return $result->getResult();
    }

}
