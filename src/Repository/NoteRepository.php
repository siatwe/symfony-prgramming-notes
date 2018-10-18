<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Note::class);
    }


    /**
     * @param $project
     *
     * @return Note[]
     */
    public function findByProjectName($project): array
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.project like :project')
            ->setParameter('project', '%'.$project.'%')
            ->orderBy('t.project', 'ASC')
            ->getQuery()
        ;

        return $qb->execute();
    }


    /**
     * @param $language
     *
     * @return Note[]
     */
    public function findByLanguage($language): array
    {
        $qb = $this->createQueryBuilder('l')
            ->andWhere('l.language like :language')
            ->setParameter('language', $language)
            ->orderBy('l.language', 'DESC')
            ->getQuery()
        ;

        return $qb->execute();
    }


    /**
     * @param $keyword
     *
     * @return Note[]
     */
    public function findByKeyWord($keyword): array
    {
        $qb = $this->createQueryBuilder('k')
            ->orWhere('k.title like :keyword')
            ->orWhere('k.language like :keyword')
            ->orWhere('k.project like :keyword')
            ->orWhere('k.content like :keyword')
            ->orWhere('k.comment like :keyword')
            ->orWhere('k.date like :keyword')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->orderBy('k.title', 'DESC')
            ->getQuery()
        ;
        dump($qb);

        return $qb->execute();
    }


    /**
     * @param $date
     *
     * @return Note[]
     */
    public function findByDate($date): array
    {
        $qb = $this->createQueryBuilder('d')
            ->andWhere('d.date like :date')
            ->setParameter('date', $date)
            ->orderBy('d.date', 'DESC')
            ->getQuery()
        ;

        return $qb->execute();
    }
}
