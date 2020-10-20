<?php

namespace App\Repository;

use App\Entity\Animer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Animer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animer[]    findAll()
 * @method Animer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animer::class);
    }

    /**
     * @return Animer[] Returns an array of Animer objects
     */

    public function findByFormationId($idFormation)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.fkAnimerFormation = :id')
            ->setParameter('id', $idFormation)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Animer
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
