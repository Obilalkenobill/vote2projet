<?php

namespace App\Repository;

use App\Entity\GroupPers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupPers|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupPers|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupPers[]    findAll()
 * @method GroupPers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupPersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupPers::class);
    }

    // /**
    //  * @return GroupPers[] Returns an array of GroupPers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupPers
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
