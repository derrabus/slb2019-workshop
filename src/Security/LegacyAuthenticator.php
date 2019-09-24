<?php

namespace TravelOrganizer\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class LegacyAuthenticator extends AbstractGuardAuthenticator
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('auth_login'));
    }

    public function supports(Request $request): bool
    {
        return \Zend_Auth::getInstance()->hasIdentity();
    }

    public function getCredentials(Request $request)
    {
        return \TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser();
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $credentials;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
