<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getAvailableRooms($date_start, $date_final)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();


        $qb = $em->createQueryBuilder();

        $nots = $em->createQuery("
    	SELECT IDENTITY(b.room) FROM App:Booking b
        	WHERE NOT (b.endAt   < '$date_start'
           	OR
           	b.beginAt > '$date_final')
    	");

        $dql_query = $nots->getDQL();
        $qb->resetDQLParts();


        $query = $qb->select('r')
            ->from('App:Room', 'r')
            ->where($qb->expr()->notIn('r.id', $dql_query ))
            ->getQuery()
            ->getResult();

        try {

            return $query;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
