class BlockLoginListener {

private $attempt;
private $router;


public function __construct(SessionInterface $session, RouterInterface $router){
    $this->attempt = $session->get(LoginAttempt::LOGIN_ATTEMPT, null);
    $this->router = $router;
}

public function onKernelRequest(GetResponseEvent $event){

    if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
        return;
    }

    if(null !== $this->attempt){
        if($this->attempt->isLocked()){
            $message = sprintf('Too much attempts, your account has been locked for %d minutes', $this->attempt->getLockInterva());

            $url = $this->router->generate("test",array("message" => $message));

            //$event->stopPropagation();
            $event->setResponse(new RedirectResponse($url));

        }
    }

}
}