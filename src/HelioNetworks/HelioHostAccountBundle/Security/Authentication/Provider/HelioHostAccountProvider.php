<?php

namespace HelioNetworks\HelioHostAccountBundle\Security\Authentication\Provider;

use HelioNetworks\HelioHostAccountBundle\Security\Authentication\Token\HelioHostAccountToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class HelioHostAccountProvider implements AuthenticationProviderInterface
{
    public function supports(TokenInterface $token)
    {
        return $token instanceof HelioHostAccountToken;
    }
}