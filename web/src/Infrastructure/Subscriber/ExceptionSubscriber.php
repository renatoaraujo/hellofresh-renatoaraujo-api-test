<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $message = \json_encode([
            'error' => [
                'message' => sprintf('An error has occurred: %s with code: %s', $exception->getMessage(),
                    $exception->getCode()),
            ],
        ]);

        $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $headers = ['Content-Type' => 'application/json'];

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        }

        $response = new JsonResponse($message, $statusCode, $headers, true);
        $event->setResponse($response);
    }
}
