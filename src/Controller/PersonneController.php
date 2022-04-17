<?php

namespace App\Controller;

use Exception;
use App\Entity\Role;
use App\Entity\Personne;
use App\Entity\RolePers;
use App\Model\PersonneDTO;
use FOS\RestBundle\View\View;
use App\Repository\RoleRepository;
use App\Repository\PersonneRepository;
use App\Repository\RolePersRepository;
use App\Repository\ReceptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PersonneController extends AbstractFOSRestController
{


    /**
     * @Route("/personne/xml", name="personnebis")
     */
    public function indexml(SerializerInterface $serializer): Response
    {

        $repo=$this->getDoctrine()->getRepository(Personne::class);
        $personnes=$repo->findAll();

        $personnes=$serializer->serialize($personnes, 'xml');


        $response = new Response($personnes);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
    
    /**
     * @Rest\Get(path="/api/personne", name="personne_getall")
     * @Rest\View()
     * @return View
     */
    public function getAll(PersonneRepository $repo)
    {
        return $this->view([
            "Personnes"=>$repo->findAll()
         ]);
    }
        /**
     * @Rest\Get(path="/api/personne/contact/{UserId}", name="personne_getallbis")
     * @Rest\View()
     * @return View
     */
    public function getAllbis($UserId,PersonneRepository $repo)
    {
        return $this->view([
            "Personnes"=>$repo->findAllbis($UserId)
         ]);
    }

        
    /**
     * @Rest\Get(path="/api/personne/invitation/{id}", name="personne_get_invit")
     * @Rest\View()
     * @return View
     */
    public function getInvit_Perso($id, PersonneRepository $repo)
    {

        return $this->view([
            "Personnes"=>$repo->find_Invit_perso($id)
         ]);
    }

            
    /**
     * @Rest\Get(path="/api/personne/ami/{id}", name="personne_get_ami")
     * @Rest\View()
     * @return View
     */
    public function getAmi($id, PersonneRepository $repo)
    {

        return $this->view([
            "Personnes"=>$repo->find_ami($id)
         ]);
    }

    /**
     * @Rest\Put(path="/api/personne/invitation/{id1}/{id2}", name="personne_add_contact")
     * @Rest\View()
     * @return View
     */
    public function putAddContact($id1,$id2, PersonneRepository $repo)
    {

        return $this->view([
            "Personnes"=>$repo->add_contact($id1,$id2)
         ]);
    }
    

       /**
     * @Rest\Put(path="/api/personne/contact/retirer/{id1}/{id2}", name="personne_retirer_ami")
     * @Rest\View()
     * @return View
     */
    public function retirer_ami($id1,$id2, PersonneRepository $repo)
    {

        return $this->view([
            "Personnes"=>$repo->retirer_ami($id1,$id2)
         ]);
    }



        /**
     * @Rest\Put(path="/api/personne/invitation/accepter/{id1}/{id2}", name="personne_acc_invit")
     * @Rest\View()
     * @return View
     */
    public function accepter_invit($id1,$id2, PersonneRepository $repo)
    {

        return $this->view([
            "Personnes"=>$repo->accepter_invit($id1,$id2)
         ]);
    }


      /**
     * @Rest\Put("api/users/validate/{personne}", name="app_activationUser")
     */
    public function activate (Personne $personne){
            $personne->setIsVerified(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
            //RedirectResponse
            return $this->view([
                'activate',Response::HTTP_ACCEPTED
             ]);
        }

      /**
     * @Rest\Get(path="/api/users/name/{login}", name="personne_getbylogin")
     */
    public function getbyLogin($login)
    {
        $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
        $personne=$personneRepo->findOneBy(['login' => $login]);
        if($personne instanceof Personne) {

            return $this->view([
                true
             ]);
            }
        else{
            return $this->view([
                false
             ]);
        }    
    }
    /**
     * @Rest\Get(path="/api/users/email/{email}", name="personne_getbyemail")
     */
    public function getbyEmail($email)
    {
        $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
        $personne=$personneRepo->findOneBy(['email' => $email]);
        if($personne instanceof Personne) {

            return $this->view([
                true
             ]);
            }
        else{
            return $this->view([
                false
             ]);
        }    

    }


    /**
     * @Rest\Get(path="/api/users/id/{personne}", name="personne_getID")
     */
     public function getbyId(Personne $personne, PersonneRepository $repo)
     {
        //  $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
        //  $personne=$personneRepo->findOneBy(['id' => $id]);
        $personnebis=$repo->findOneByIDbis($personne->getId());
        $personne->replaceBlob();
        $personnebis[0]['photoverif']=$personne->getPhotoverif();
       $personnebis[0]['rectocarteid']=$personne->getrectocarteid();
       $personnebis[0]['versocarteid']=$personne->getversocarteid();
        return $this->view([
           $personnebis
          ]);
     }
         /**
     * @Rest\Delete(path="/api/users/delete/{personne}", name="delete_personne_getID")
     */
    public function deleteUser(Personne $personne, RolePersRepository $repoRolePers)
    {
       //  $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
       //  $personne=$personneRepo->findOneBy(['id' => $id]);
       $test=$repoRolePers->deleteAboutUser($personne);
       $em = $this->getDoctrine()->getManager();
       $em->remove($personne);
       $em->flush(); 
       
       return $this->view([
           'deleted',Response::HTTP_ACCEPTED
         ]);
    }
         /**
     * @Rest\Get(path="/api/users/nn/{nn}", name="personne_getbyNN")
     */
    public function getbynn($nn)
    {
        $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
        $personne=$personneRepo->findOneBy(['nn' => $nn]);
        if($personne instanceof Personne) {

            return $this->view([
                true
             ]);
            }
        else{
            return $this->view([
                false
             ]);
        }    
    }
   
   /**
     * @Rest\Get(path="/api/personne/group/{userid}", name="personne_get_group")
     * @Rest\View()
     * @param EntityManagerInterface $em
     * @param Request $req
     * @return View
     */
    public function getGroup($userid,Request $req, EntityManagerInterface $em, ReceptionRepository $repo) {
          return $this->view([
           $repo->selectGroupMessage($userid)
           ]);
      
    }

       /**
     * @Rest\Get(path="/api/personne/voirMessPartic/{groupid}", name="personne_get_mess_part")
     * @Rest\View()
     * @param EntityManagerInterface $em
     * @param Request $req
     * @return View
     */
    public function getMessPartic($groupid, ReceptionRepository $repo) {
$ok=$repo->selecMessPartic($groupid);
        return $this->view([
         $repo->selecMessPartic($groupid)
         ]);
    
  }
/**
     * @Rest\Post(path="/api/personne/creategroup/{nom_groupe}", name="personne_create_group")
     * @Rest\View()
     * @param EntityManagerInterface $em
     * @param Request $req
     * @return View
     */
    public function createGroup($nom_groupe,Request $req, ReceptionRepository $repo) {
        $tab=[];
        $i=0;
        $user_init=$req->request->get("pers_init");
        dump($user_init);
        while($req->request->get($i)!= null ) {
            array_push($tab,$req->request->get($i));
            $i++;
          }

          return $this->view([
            $repo->insertGroup($nom_groupe,$tab,$user_init)
           ]);
    
    }
    /**
     * @Rest\Post(path="/api/personne/imageverif", name="personne_image_verif")
     * @Rest\View()
     * @param EntityManagerInterface $em
     * @param Request $req
     * @return View
     */
    public function pathImageVerif(Request $req, EntityManagerInterface $em, PersonneRepository $repo) {
        $personne=$repo->findOneBy( [ 'id' => ( $req->request->get('UserId') ) ] );
       try{
       $filephotoverif=$req->files->get('filephotoverif');
       $filerectocarteid=$req->files->get('filerectocarteid');
       $fileversocarteid=$req->files->get('fileversocarteid');
       }
       catch(Exception $ex)
       {
           
       }
        if( $filephotoverif!=null ){
            $bin=file_get_contents($filephotoverif->getPathname());
            $personne->setphotoverif($bin);
            $personne->setmimeTypephotoverif($filephotoverif->getMimeType());
            $em=$this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
        }
        if( $filerectocarteid!=null ){
            $bin=file_get_contents($filerectocarteid->getPathname());
            $personne->setrectocarteid($bin);
            $personne->setmimeTyperectocarteid($filerectocarteid->getMimeType());
            $em=$this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
        }
        if( $fileversocarteid!=null ){
            $bin=file_get_contents($fileversocarteid->getPathname());
            $personne->setversocarteid($bin);
            $personne->setmimeTypeversocarteid($fileversocarteid->getMimeType());
            $em=$this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
        }
        

        if (empty($personne->getRolePers()) && $personne->getPhotoverif()!=null && $personne->getrectocarteid()!=null && $personne->getversocarteid()!=null)
        {
            $UserID=$personne->getId();
            $repo->setROLE_USERtoUserID($UserID);
        }        
    }
}
