<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\RolePers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RolePers|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolePers|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolePers[]    findAll()
 * @method RolePers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolePersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RolePers::class);
    }

    public function deleteRoleUser(RolePers $role_pers)
    {
        $conn = $this->getEntityManager()->getConnection();
        $personne_id=$role_pers->getPersonneId()->getId();
        $role_id=$role_pers->getRoleId()->getId();
        $sql = '
          DELETE FROM RolePers WHERE personne_id='.$personne_id.' and role_id='.$role_id;
        // returns an array of arrays (i.e. a raw data set)
        return $conn->fetchAllAssociative($sql);
    }
    public function deleteAboutUser(Personne $personne)
    {
        $conn = $this->getEntityManager()->getConnection();
        $personne_id=$personne->getId();
        $sql = '
          DELETE FROM RolePers WHERE personne_id='.$personne_id.';DELETE FROM vote where personne_id='.$personne_id.';DELETE FROM Follow where personne_id_id='.$personne_id.';DELETE FROM commentaire where personne_id_id='.$personne_id.';';
          $conn->fetchAllAssociative($sql);
        $sql2='DELETE FROM projet WHERE personne_id_id='.$personne_id;
        $result=$conn->fetchAllAssociative($sql2);
        // returns an array of arrays (i.e. a raw data set)
        return $result;
    }
    public function deleteRole(RolePers $role_pers)
    {
        $conn = $this->getEntityManager()->getConnection();
        $role_id=$role_pers->getRoleId()->getId();
        $sql = '
          DELETE FROM RolePers WHERE role_id='.$role_id.';DELETE FROM role WHERE id='.$role_id;
          $result= $conn->fetchAllAssociative($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $result;
    }
}
