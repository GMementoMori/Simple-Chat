<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return array
     */

    public function findUnreadByRecipient($userId): array
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.email_sender, COUNT(m) as cnt')
            ->distinct()
            ->andWhere('m.id_recipient = :id_recipient')
            ->andWhere('m.is_checked = :is_checked')
            ->setParameters(['id_recipient' => $userId, 'is_checked' => 0])
            ->groupBy('m.email_sender')
            ->getQuery();
        return $result->getResult();
    }

    public function findBySenderRecipient($idSender, $idRecipient): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT m
                    FROM App\Entity\Message m
                    WHERE ( m.id_sender = :id_sender
                    AND m.id_recipient = :id_recipient )
                    OR ( m.id_sender = :id_recipient
                    AND m.id_recipient = :id_sender )'
        )
            ->setParameters(['id_sender' => $idSender, 'id_recipient' => $idRecipient])
            ->getResult();

    }

}
