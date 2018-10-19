<?php

namespace App\Repository;

use App\Entity\Crib;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Crib|null find($id, $lockMode = null, $lockVersion = null)
 * @method Crib|null findOneBy(array $criteria, array $orderBy = null)
 * @method Crib[]    findAll()
 * @method Crib[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CribRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Crib::class);
    }

//    /**
//     * @return Crib[] Returns an array of Crib objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Crib
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
