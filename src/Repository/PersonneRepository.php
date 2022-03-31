<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function find_Invit_perso($id){
        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT * FROM (
        SELECT DISTINCT p.id,p.nom,p.prenom,p.login,co.personne1_id_id,co.personne2_id_id,co.accept_personne1,co.accept_personne2 
                FROM    personne p
                INNER JOIN contact co 
                ON p.id=co.personne1_id_id
                WHERE 
                (co.personne2_id_id='.$id.' OR co.personne1_id_id='.$id.') 
                AND (co.accept_personne1=1 XOR co.accept_personne2=1)
                UNION ALL
                SELECT DISTINCT p.id,p.nom,p.prenom,p.login,co.personne1_id_id,co.personne2_id_id,co.accept_personne1,co.accept_personne2 
                FROM    personne p
                INNER JOIN contact co 
                ON p.id=co.personne2_id_id
                WHERE 
                (co.personne2_id_id='.$id.' OR co.personne1_id_id='.$id.') 
                AND (co.accept_personne1=1 XOR co.accept_personne2=1) ) t
           WHERE t.id !='.$id.';';
      

 
 
  // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);;
    }
    public function accepter_invit($id1,$id2){
        $date=new \DateTime("now");
        $conn = $this->getEntityManager()->getConnection();
        $sql='UPDATE contact SET accept_personne2=1 WHERE personne1_id_id='.$id1.' AND personne2_id_id='.$id2.';';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    // UPDATE commentaire SET commentaire ="'.$commentaire.'",creation_date="'.$creation_date.'" WHERE  id='.$id.';';
    public function annuler_invit($id1,$id2){
        $date=new \DateTime("now");
        $conn = $this->getEntityManager()->getConnection();
        $sql='UPDATE contact SET accept_personne1=0 WHERE personne1_id_id='.$id1.' AND personne2_id_id='.$id2.';';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    public function refuser_invit($id1,$id2){
        $date=new \DateTime("now");
        $conn = $this->getEntityManager()->getConnection();
        $sql='UPDATE contact SET accept_personne1=0 WHERE personne1_id_id='.$id1.' AND personne2_id_id='.$id2.';';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }

    public function add_contact($id1,$id2){
        $date=new \DateTime("now");
        $conn = $this->getEntityManager()->getConnection();
        $sql='INSERT INTO contact(personne1_id_id,personne2_id_id,accept_personne1)
        VALUES
        ('.$id1.','.$id2.',1 );';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }

    public function add_contact2($id1,$id2){
        $date=new \DateTime("now");
        $conn = $this->getEntityManager()->getConnection();
        $sql='UPDATE contact SET accept_personne1=1 WHERE personne1_id_id='.$id1.' AND personne2_id_id='.$id2.';';
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    public function setRole($personne_id,$role_id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
           INSERT INTO role_pers (personne_id_id,role_id_id)
           VALUES ('.$personne_id.','.$role_id.');
            ';
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }

    public function setROLE_USERtoUserID($UserID){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
           SELECT id FROM role WHERE label="ROLE_USER"
            ';

        // returns an array of arrays (i.e. a raw data set)
        $role_id=$conn->fetchAllAssociative($sql);
        $role_id=$role_id[0]["id"];
        $sql = '
        INSERT INTO rolepers (personne_id,role_id) values('.$UserID.','.$role_id.');
         ';
     return $conn->fetchAllAssociative($sql);
    }


    public function findOneByIDbis($id){
$conn = $this->getEntityManager()->getConnection();
$sql='SELECT salt, id, nom, prenom, login, nn, creation_date, is_active, is_verified, photoverif, mime_typephotoverif, rectocarteid, mime_typerectocarteid, versocarteid, mime_typeversocarteid from personne where id='.$id.';';
$personne=$conn->fetchAllAssociative($sql);
return $personne;
    }


    public function findAllbis($UserId){
        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT DISTINCT * FROM (
            SELECT DISTINCT p.id,p.nom,p.prenom,p.login,p.email,p.is_active,p.creation_date,p.is_verified,p.nn,
             c.personne1_id_id, c.personne2_id_id, c.accept_personne1, c.accept_personne2 
             FROM personne p 
             left JOIN contact c ON (p.id=c.personne1_id_id OR p.id=c.personne2_id_id) AND (c.personne1_id_id ='.$UserId.' OR c.personne2_id_id='.$UserId.' ) ) p 
             WHERE p.id !='.$UserId.'; ';
      
  
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
