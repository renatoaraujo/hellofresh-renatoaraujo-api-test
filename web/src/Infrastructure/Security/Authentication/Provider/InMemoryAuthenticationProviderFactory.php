<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserChecker;

final class InMemoryAuthenticationProviderFactory
{
    public static function build(): AuthenticationManagerInterface
    {
        $userProvider = new InMemoryUserProvider([
            'admin' => [
                'password' => 'admin',
                'roles' => ['ROLE_ADMIN'],
            ],
        ]);

        $encoderFactory = new EncoderFactory([
            'Symfony\Component\Security\Core\User\User' => new PlaintextPasswordEncoder(),
        ]);

        $userChecker = new UserChecker();
        $providers = [
            new DaoAuthenticationProvider($userProvider, $userChecker, 'main', $encoderFactory, true),
        ];

        return new AuthenticationProviderManager($providers, true);
    }
}
