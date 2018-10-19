<?php

namespace App\Repository;

use App\Entity\CribContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CribContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CribContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CribContent[]    findAll()
 * @method CribContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CribContentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CribContent::class);
    }

//    /**
//     * @return CribContent[] Returns an array of CribContent objects
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
    public function findOneBySomeField($value): ?CribContent
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
