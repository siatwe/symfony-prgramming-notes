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


    /**
     * @param $searchString
     *
     * @return Crib[]
     */
    public function findBySearchString($searchString): array
    {
        $query = $this
            ->createQueryBuilder('s')
            ->orWhere('s.title like :searchString')
            ->orWhere('s.project like :searchString')
            ->orWhere('s.date like :searchString')
            ->orWhere('s.editDate like :searchString')
            ->innerJoin('s.cribContent', 'c')
            ->addSelect('c')
            ->orWhere('c.code like :searchString')
            ->orWhere('c.comment like :searchString')
            ->orWhere('c.language like :searchString')
            ->setParameter('searchString', '%'.$searchString.'%')
            ->orderBy('s.date', 'DESC')
            ->getQuery()
        ;

        return $query->execute();
    }
}
