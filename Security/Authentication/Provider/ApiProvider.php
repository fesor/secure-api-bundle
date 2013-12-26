<?php

namespace Darsadow\Bundle\SecureApiBundle\Security\Authentication\Provider;

use Darsadow\Bundle\SecureApiBundle\Security\Authentication\Token\ApiToken;
use Darsadow\Bundle\SecureApiBundle\Security\User\ApiUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiProvider implements AuthenticationProviderInterface
{
    /**
     * @var \Darsadow\Bundle\SecureApiBundle\Security\User\ApiUserProviderInterface
     */
    private $userProvider;

    public function __construct(ApiUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByTokenAndDevice($token->authenticationToken, $token->userAgent);

        if ($user) {
            $authenticatedToken = new ApiToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('API authentication failed.');
    }

    /**
     * @inheritdoc
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiToken;
    }


} 
