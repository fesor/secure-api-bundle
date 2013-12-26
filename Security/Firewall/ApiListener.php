<?php

namespace Darsadow\Bundle\SecureApiBundle\Security\Firewall;

use Darsadow\Bundle\SecureApiBundle\Security\Authentication\Token\ApiToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class ApiListener implements ListenerInterface
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    protected $authenticationManager;

    function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->headers->has('Authentication')) {
            $event->setResponse($this->createForbiddenResponse());

            return;
        }

        $authenticationToken = $request->headers->get('Authentication');
        $userAgent = $request->headers->get('user-agent');

        $token = new ApiToken();
        $token->authenticationToken = $authenticationToken;
        $token->userAgent = $userAgent;

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);

            return;
        }
        catch(AuthenticationException $e) {
            $event->setResponse($this->createForbiddenResponse());
        }

        $event->setResponse($this->createForbiddenResponse());
    }

    /**
     * @return Response
     */
    protected function createForbiddenResponse()
    {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);

        return $response;
    }
}
