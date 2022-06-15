<?php

namespace App\Repository;

use App\Entity\GroupGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/***
 * @method GroupGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupGroup[]    findAll()
 * @method GroupGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 **/
class GroupGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupGroup::class);
    }

    // /***
    //  * @return GroupGroup[] Returns an array of GroupGroup objects
    //  **/
    /**
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
    **/

    /**
    public function findOneBySomeField($value): ?GroupGroup
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    **/
}
