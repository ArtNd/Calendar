<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\RoomSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
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

    public function getAvailableRooms(RoomSearch $search)
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();

        $date_start = $search->getDateFrom()->format('Y-m-d H:i:s');
        $date_final = $search->getDateTo()->format('Y-m-d H:i:s');

        $nots = $em->createQuery("
    	SELECT IDENTITY (b.room) FROM App:Booking b
        	WHERE NOT (b.endAt   < '$date_start'
           	OR
           	b.beginAt > '$date_final')
    	");

        $dql_query = $nots->getDQL();
        $qb->resetDQLParts();


        $query = $qb->select('r')
            ->from('App:Room', 'r')
            ->andWhere($qb->expr()->notIn('r.id', $dql_query ));

        if ($search->getMinCapacity())
        {
            $query =  $query
                ->andWhere('r.capacity >= :mincapacity')
                ->setParameter('mincapacity', $search->getMinCapacity());
        }

        if ($search->getOptions()->count() > 0)
        {
            $k = 0;
            foreach ($search->getOptions() as $option)
            {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF r.options")
                    ->setParameter("option$k", $option);
            }
        }

        try
        {
            return $query->getQuery()->getResult();
        }
        catch (\Doctrine\ORM\NoResultException $e)
        {
            return null;
        }
    }
}
