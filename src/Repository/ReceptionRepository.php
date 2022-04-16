<?php

namespace App\Repository;

use App\Entity\Reception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reception|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reception|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reception[]    findAll()
 * @method Reception[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reception::class);
    }


         public function insertGROUP($nom_groupe,$UserIds){

         
                    $conn = $this->getEntityManager()->getConnection();
                    $sql='INSERT INTO group_group(name) VALUES ( "'.$nom_groupe.'" );';
                    $sql2='SET @id_group = LAST_INSERT_ID();';
                    $sql3="";
                        for ($i=0; $i < sizeof($UserIds) ; $i++) { 
                            $sql3=$sql3.' INSERT INTO group_pers (group_group_id_id,personne_id_id) 
                            VALUES (@id_group, '.$UserIds[$i].') ; ';
                        }   
                
                    $sql_final=$sql.$sql2.$sql3;
                    dump($sql_final);
                    $conn->fetchAllAssociative($sql_final);
         }

         
    public function findAllMessage($UserIds){
   

        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT p.id, m.expediteur_id, m.message_txt,r.destinaire_s_id , p.login 
        FROM message m 
        JOIN reception r 
        ON m.id=r.message_id_id 
        JOIN personne p 
        ON m.expediteur_id=p.id
        ORDER BY r.destinaire_s_id;';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }

    

public function selectGroupMessage($UserId){


        $conn = $this->getEntityManager()->getConnection();
        // $sql='SELECT g_p.personne_id_id AS id ,g_g.id AS group_group_id_id,
        // p.login 
        // FROM group_group g_g  
        // JOIN group_pers g_p 
        // ON g_p.group_group_id_id = g_g.id 
        // JOIN personne p
        // ON p.id = g_p.personne_id_id
        // JOIN
        // (SELECT gp.group_group_id_id 
        // FROM group_pers gp WHERE gp.personne_id_id='.$UserId.') t2 ON t2.group_group_id_id=g_p.group_group_id_id';
                $sql1='SELECT g_g.name ,g_g.id 
                FROM group_group g_g  
                JOIN group_pers g_p 
                ON g_p.group_group_id_id = g_g.id 
                JOIN personne p
                ON p.id = g_p.personne_id_id
               WHERE p.id=3 ';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql1);
    }
// /**
    //  * @return Reception[] Returns an array of Reception objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reception
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
