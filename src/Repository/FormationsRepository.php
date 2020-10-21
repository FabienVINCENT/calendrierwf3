<?php

namespace App\Repository;

use App\Entity\Formations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formations[]    findAll()
 * @method Formations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formations::class);
    }

    /**
     * Function qui permet de recuperer la liste des formations ainsi que l'endroit
     * {
     *  "id": 1,
     *  "nom": "Devellopeur web",
     *  "localisation_id": 4,
     *  "ville": "Hardy"
     * }
     */
    public function getListFormation()
    {
        return $this->createQueryBuilder('f')
            ->select('f.id, f.nom, fl.id as localisation_id, fl.ville')
            ->leftJoin('f.localisation', 'fl')
            ->getQuery()
            ->getResult();
    }

    public function getListFormationById($value)
    {
        return $this->createQueryBuilder('f')
            ->select('f.id, f.nom, fl.id as localisation_id, fl.ville')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->leftJoin('f.localisation', 'fl')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Formations[]
     */
    public function getFormationEvent()
    {
        return $this->createQueryBuilder('f')
            // ->select('f.id, f.nom, fl.id as localisation_id, fl.ville,f.dateDebut, f.dateFin')
            ->leftJoin('f.localisation', 'fl')
            ->andWhere('f.dateFin >= CURRENT_DATE()')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Formations[]
     */
    public function getCurrentFormation()
    {
        return $this->createQueryBuilder('f')
            //->select('f.id, f.nom, fl.id as localisation_id, fl.ville,f.dateDebut, f.dateFin')
            //->leftJoin('f.localisation', 'fl')
            ->Where('f.dateFin >= CURRENT_DATE()')
            ->andWhere('f.dateDebut <= CURRENT_DATE()')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Formations[] Returns an array of Formations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formations
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
