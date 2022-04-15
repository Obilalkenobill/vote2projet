<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\RegisterType;
use App\Model\PersonneDTO;
use FOS\RestBundle\View\View;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractFOSRestController
{

    // /**
    //  * @Route("api/login_check", name="login")
    //  */
    // public function login(): JsonResponse
    // {
        
    //     // if ($this->getUser()) {
    //     //     return $this->redirectToRoute('target_path');
    //     // }

    //     // get the login error if there is one
    //    $user=$this->getUser();
    //    $roles=[];
    //    foreach($user->getRolePers() as $rolePers)
    //        {
    //        $roles[]=$rolePers->getRoleId()->getLabel();
    //        }
    //    return $this->json( array(  'username'=> $user->getUsername(), 'roles'=>$roles,'nom'=>$user->getNom() ) );
    // }

    // /**
    //  * @Route("api/login", name="app_login")
    //  */
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     $
    //     // if ($this->getUser()) {
    //     //     return $this->redirectToRoute('target_path');
    //     // }

    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    // }

    /**
     * @Route("api/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("api/activate/{salt}/{id}", name="app_activation")
     */
    public function activate ($salt,? Personne $personne){
        $personneRepo=$this->getDoctrine()->getRepository(Personne::class);
        $personnebis=$personneRepo->findOneBy(['salt' => $salt]);
        if (isset($personnebis) && isset($personne) && $personnebis->getId()==$personne->getId())
        {
            $personne->setIsActive(true);
            $personne->setCreationDate(new \DateTime("now"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
            //RedirectResponse
            return $this->redirect("http://localhost:4200/");
        }
        return $this->redirect("http://localhost:4200/");

    }

    /**
     * @Rest\Post("/api/register", name="app_register")
     * @Rest\View()
     * @ParamConverter("dto",converter="fos_rest.request_body")
     */
    public function register(ConstraintViolationList $violations,PersonneDTO $dto,UserPasswordHasherInterface $hasher, MailerInterface $mailer){
     if (sizeof($violations) > 0){
         return $this->view(["errors" => $violations]);
     }
        $salt=uniqid();
        $personne=$dto->toEntity();
        $personne->setSalt($salt);
        // $hasher = $hasherFactory->getPasswordHasher($personne->getPassword());
        
        // $hashedPassword = $hasher->hash($personne->getPassword());
        
        // $personne->setPassword($hashedPassword);
        // $encoded = $encoder->encodePassword($personne, $personne->getPassword());
        // $personne->setPassword($encoded);
        $personne->setPassword($hasher->hashPassword($personne, $personne->getPassword()));
        $personne->setIsActive(false);
        $personne->setIsVerified(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($personne);
        $em->flush();
        $email=new Email() ;
        $email->to($personne->getEmail());
        $email->subject('Bienvenue sur le site du referendum à initiative citoyen');
        $email->text('https://localhost:8000/api/activate/'.$personne->getSalt().'/'.$personne->getId().' Veuillez cliquer sur ce lien pour activer votre compte');
        $email->html('<div><a href="https://localhost:8000/api/activate/'.$personne->getSalt().'/'.$personne->getId().'">Veuillez cliquer sur ce lien pour activer votre compte </a></div>');
        $email->from("arbreplantebuisson@gmail.com");
        $mailer->send($email);
        return $this->view(["personne"=> $personne],Response::HTTP_CREATED);
    }
}
