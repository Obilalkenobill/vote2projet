<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Role;
use App\Entity\RolePers;
use App\Model\PersonneDTO;
use FOS\RestBundle\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\PersonneRepository;
use App\Repository\RolePersRepository;
use App\Repository\RoleRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;

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
            "Personnes"=>$repo->findAllbis()
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
     * @Rest\Post(path="/api/personne/imageverif", name="personne_image_verif")
     * @Rest\View()
     * @param EntityManagerInterface $em
     * @param Request $req
     * @return View
     */
    public function pathImageVerif(Request $req, EntityManagerInterface $em, PersonneRepository $repo) {

        // if (sizeof($violations) > 0) {
        //     return $this->view(["errors" => $violations]);
        // }
       try{
       $filephotoverif=$req->files->get('filephotoverif');
       $filerectocarteid=$req->files->get('filerectocarteid');
       $fileversocarteid=$req->files->get('fileversocarteid');
       }
       catch(Exception $ex)
       {
       }
           $personne=$repo->findOneBy( [ 'id' => ( $req->request->get('UserId') ) ] );
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
