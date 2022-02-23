<?php

namespace App\Repository;

use App\Entity\DemandeFormateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemandeFormateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeFormateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeFormateur[]    findAll()
 * @method DemandeFormateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeFormateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeFormateur::class);
    }

    // /**
    //  * @return DemandeFormateur[] Returns an array of DemandeFormateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DemandeFormateur
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
