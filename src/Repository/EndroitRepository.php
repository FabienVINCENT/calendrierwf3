<?php

namespace App\Repository;

use App\Entity\Endroit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Endroit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Endroit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Endroit[]    findAll()
 * @method Endroit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndroitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Endroit::class);
    }

    // /**
    //  * @return Endroit[] Returns an array of Endroit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Endroit
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
