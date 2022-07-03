<?php

namespace App\Repository;

use App\Entity\SignalCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignalCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalCommentaire[]    findAll()
 * @method SignalCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalCommentaire::class);
    }

    // /**
    //  * @return SignalCommentaire[] Returns an array of SignalCommentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SignalCommentaire
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    function findAllbis(){
        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT sc.id,sc.descriptif,sc.personne_id_id,sc.commentaire_id_id,p.login,com.is_lock, sc.creation_date,p.nom,p.prenom FROM signal_commentaire sc JOIN personne p ON p.id=sc.personne_id_id JOIN commentaire com ON sc.commentaire_id_id=com.id';

          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    function putSignalCommStatus($id_signal_comm,$status){
        $conn = $this->getEntityManager()->getConnection();
        $sql=' UPDATE commentaire SET is_lock="'.$status.'" WHERE  id='.$id_signal_comm.';';

          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
}
