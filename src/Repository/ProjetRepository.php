<?php

namespace App\Repository;

use App\Entity\Projet;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    public function getNbreBullNull(Projet $projet)
    {
        $conn = $this->getEntityManager()->getConnection();
        $projet_id=$projet->getId();
        $sql = '
        SELECT COUNT(*)
        FROM vote
        WHERE projet_id='.$projet_id.' AND bull_vote is null;';
    
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }

    public function getNbreBullPour(Projet $projet)
    {
        $conn = $this->getEntityManager()->getConnection();
        $projet_id=$projet->getId();
        $sql = '
        SELECT COUNT(*)
        FROM vote
        WHERE projet_id='.$projet_id.' AND bull_vote=1;';
 
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }
    public function getNbreBullContre(Projet $projet)
    {
        $conn = $this->getEntityManager()->getConnection();
        $projet_id=$projet->getId();
        $sql = '
        SELECT COUNT(*)
        FROM vote
        WHERE projet_id='.$projet_id.' AND bull_vote=0;';
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }
    public function findProjetByUserRPO($personne_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT 
        p.id,p.personne_id_id,p.titre,p.nbr_vote_pour,p.nbr_vote_null,p.nbr_vote_contre, p.descriptif,p.date_adm,p.date_rej,p.creation_date,v.id as vote_id,v.bull_vote,v.a_vote 
        FROM projet p
        LEFT JOIN vote v 
        on p.id=v.projet_id 
        WHERE p.personne_id_id='.$personne_id;
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }

    public function findProjetByFollower($personne_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT p.id,p.personne_id_id,p.titre,p.nbr_vote_pour,p.nbr_vote_null,p.nbr_vote_contre, p.descriptif,p.date_adm,p.date_rej,p.creation_date,v.id as vote_id,v.bull_vote,v.a_vote 
        FROM projet p  
        INNER JOIN Follow f  
        ON p.id = f.projet_id_id 
        LEFT JOIN vote v 
        on p.id=v.projet_id 
        AND v.personne_id='.$personne_id.'
        WHERE f.personne_id_id='.$personne_id;
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }

   public function findCommentByProjetID($projet_id){
    $conn = $this->getEntityManager()->getConnection();
    $sql = 'SELECT *
    FROM commentaire c  
    WHERE c.projet_id_id='.$projet_id;
    // returns an array of arrays (i.e. a raw data set)
    return $conn->fetchAllAssociative($sql);
   }

    public function deleteProjet($id){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'DELETE FROM vote WHERE projet_id='.$id.';DELETE FROM Follow WHERE projet_id_id='.$id;
        $conn->fetchAllAssociative($sql);
        $sql ='DELETE FROM commentaire WHERE commentaire_referent_id_id IS NOT NULL AND projet_id_id='.$id.';';
        $conn->fetchAllAssociative($sql);
        $sql ='DELETE FROM commentaire WHERE projet_id_id='.$id.';';
        $conn->fetchAllAssociative($sql);
        $sql ='DELETE FROM projet WHERE id='.$id;

        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }

    public function deleteFollow($projet_id,$personne_id){

        $conn = $this->getEntityManager()->getConnection();
      $sql='DELETE FROM Follow WHERE projet_id_id='.$projet_id.' AND personne_id_id='.$personne_id.';';

        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }    
    
    public function save_follow($personne_id,$projet_id){
        $conn = $this->getEntityManager()->getConnection();
      $sql='INSERT INTO Follow (projet_id_id,personne_id_id) VALUES ('.$projet_id.','.$personne_id.');';

        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }
    public function findAllbis($personne_id){
        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT p.id,p.personne_id_id,p.titre,p.nbr_vote_pour,p.nbr_vote_null,p.nbr_vote_contre, p.descriptif,p.date_adm,p.date_rej,p.creation_date,v.id as vote_id,v.bull_vote,v.a_vote FROM projet p LEFT JOIN vote v on p.id=v.projet_id AND v.personne_id='.$personne_id.';';
      
  
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }

    public function findOneBybis($projet_ID){
        $conn = $this->getEntityManager()->getConnection();
        $sql='SELECT * FROM projet where id='.$projet_ID;
      
  
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
    
    public function insert($titre, $descriptif,$personne_id){
        $date=new \DateTime("now");
        $creation_date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $sql='INSERT INTO projet (descriptif,titre,personne_id_id,creation_date) VALUES ("'.$descriptif.'","'.$titre.'",'.$personne_id.',"'.$creation_date.'");';
      
  
          // returns an array of arrays (i.e. a raw data set)
          return $conn->fetchAllAssociative($sql);
    }
}
