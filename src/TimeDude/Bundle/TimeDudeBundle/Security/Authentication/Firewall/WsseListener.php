<?php

namespace TimeDude\Bundle\TimeDudeBundle\Security\Authentication\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use \TimeDude\Bundle\TimeDudeBundle\Security\Authentication\Token\WsseUserToken;

class WsseListener implements ListenerInterface {

    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager) {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event) {
        $request = $event->getRequest();

        $wsseRegex = '/ApiKey="([^"]+)"/';

        $pregmatchCactcumajonejsaqer = preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches);

        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            return FALSE;
        }

        $token = new WsseUserToken();
        $token->setUser($matches[1]);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here
            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->securityContext->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->securityContext->setToken(null);
            // }
            // return;
        } catch (\Symfony\Component\Security\Core\Exception\NonceExpiredException $failed) {

        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(403);
        $event->setResponse($response);
    }

}
