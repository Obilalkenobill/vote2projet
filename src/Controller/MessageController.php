<?php

namespace App\Controller;
use App\Entity\Follow;
use App\Entity\Projet;
use App\Entity\Message;
use App\Entity\Personne;
use App\Model\ProjetDTO;
use App\Entity\GroupPers;
use App\Entity\Commentaire;
use FOS\RestBundle\View\View;
use App\Repository\FollowRepository;
use App\Repository\ProjetRepository;
use App\Repository\MessageRepository;
use App\Repository\GroupPersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MessageController extends AbstractFOSRestController
{

    /**
     * @Route("/message", name="message")
     **/
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    /**
     * @Rest\Post("/api/personne/send/message", name="app_post_message")
     * @Rest\View()
     * @ParamConverter("message",converter="fos_rest.request_body")
     **/
    public function MessagePost(Request $req, Message $message, MessageRepository $repo, GroupPersRepository $group_pers_repo){
        $em = $this->getDoctrine()->getManager();
     $message->setCreationDate(new \DateTime(), new \DateTimeZone('Europe/Paris'));
     $repo->insert_not_read($message->getGroupGroupId()->getId(),$message->getExpediteur()->getId());
     $em->persist($message);
     $em->flush();
    return $this->view();
    }
  
    /**
     * @Rest\Get (path="api/personne/message_by_group/{Groupe_ID}/{Personne_ID}",name="api_get_all_XAmessage")
     * @Rest\View()
     **/
    public function getMessageGroupe($Groupe_ID, $Personne_ID, MessageRepository $repo){
        $Messages=$repo->findMessageByGroupId($Groupe_ID,$Personne_ID);
        return $this->view( [$Messages]);
    }
}
