<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

        public function   selecMessPartic($groupid){

         
            $conn = $this->getEntityManager()->getConnection();

            $sql='SELECT DISTINCT p.id,p.login,g_g.id AS group_group_id_id FROM group_group g_g JOIN group_pers g_p ON g_g.id=g_p.group_group_id_id JOIN personne p ON 
            p.id=g_p.personne_id_id WHERE g_p.group_group_id_id='.$groupid.';';
    
   return  $conn->fetchAllAssociative($sql);

             }
    
             
         

         public function insertGROUP($nom_groupe,$UserIds,$user_init){

         
                    $conn = $this->getEntityManager()->getConnection();
                    $sql='INSERT INTO group_group(name,pers_init_id_id) VALUES ( "'.$nom_groupe.'",'.$user_init.' );';
                    $sql2='SET @id_group = LAST_INSERT_ID();';
                    $sql3="";
                        for ($i=0; $i < sizeof($UserIds) ; $i++) { 
                            $sql3=$sql3.' INSERT INTO group_pers (group_group_id_id,personne_id_id) 
                            VALUES (@id_group, '.$UserIds[$i].') ; ';
                        }   
                        $sql3=$sql3.' INSERT INTO group_pers (group_group_id_id,personne_id_id) 
                        VALUES (@id_group, '.$user_init.') ; ';
                    $sql_final=$sql.$sql2.$sql3;
                    $conn->fetchAllAssociative($sql_final);
         }

         
    public function findAllMessage($UserIds){
   

        $conn = $this->getEntityManager()->getConnection();
        $sql='';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    public function delete_user_in_group($user_id,$group_act_id){
   

        $conn = $this->getEntityManager()->getConnection();
        $sql='DELETE FROM group_pers WHERE personne_id_id='.$user_id.' AND group_group_id_id ='.$group_act_id.';';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
   public function ajouter_user_in_group($user_id,$group_act_id){
   

        $conn = $this->getEntityManager()->getConnection();
        $sql='INSERT INTO group_pers(personne_id_id,group_group_id_id) VALUES ('.$user_id.','.$group_act_id.');';
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
                $sql1='SELECT  DISTINCT g_g.name ,g_g.id,g_g.pers_init_id_id
                FROM group_group g_g  
                JOIN group_pers g_p 
                ON g_p.group_group_id_id = g_g.id 
                JOIN personne p
                ON p.id = g_p.personne_id_id
               WHERE p.id='.$UserId.' ; ';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql1);
    }
// /**
    //  * @return Message[] Returns an array of Message objects
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
    public function findOneBySomeField($value): ?Message
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
