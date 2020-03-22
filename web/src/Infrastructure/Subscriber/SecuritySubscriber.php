<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class SecuritySubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var AuthenticationManagerInterface */
    private $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest'],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->has('authorization')) {
            try {
                $username = $request->getUser();
                $password = $request->getPassword();

                $unauthenticatedToken = new UsernamePasswordToken(
                    $username,
                    $password,
                    'main'
                );

                $authenticatedToken = $this->authenticationManager->authenticate($unauthenticatedToken);
                $this->tokenStorage->setToken($authenticatedToken);
            } catch (AuthenticationException $exception) {
                $response = new JsonResponse($exception->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
                $event->setResponse($response);
            }
        }
    }
}
