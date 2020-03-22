<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TokenStorageFactory
{
    public static function build(): TokenStorageInterface
    {
        $tokenStorage = new TokenStorage();
        $token = new AnonymousToken('secret', 'anonymous', ['IS_AUTHENTICATED_ANONYMOUSLY']);
        $tokenStorage->setToken($token);

        return $tokenStorage;
    }
}
