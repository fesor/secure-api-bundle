<?php

namespace Darsadow\Bundle\SecureApiBundle\Security\User;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ApiUserProviderInterface extends UserProviderInterface
{
    /**
     * @param string $token
     * @param string $device
     * @return UserInterface
     */
    public function loadUserByTokenAndDevice($token, $device);
} 
