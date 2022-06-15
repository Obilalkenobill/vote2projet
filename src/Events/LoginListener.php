<?php
 
namespace App\Events;
 

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
 
class LoginListener
{
    /**
     * @param JWTCreatedEvent $event
     **/
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        $user = $event->getUser();
 
        if ($user instanceof Personne) {
            $payload["is_verified"]=$user->getIsVerified();
            $payload["id"]=$user->getId();
            $roles=[];
            foreach($user->getRolePers() as $rolePers)
                {
                $roles[]=$rolePers->getRoleId()->getLabel();
                }
            $payload["roles"]=$roles;

            $payload["nom"]=$user->getNom();

            $payload["login"]=$user->getLogin();
        }
        $event->setData($payload);
    }
}