<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use HelloFresh\Application\Controller\Utils\PayloadCapability;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CreateRecipe
{
    use PayloadCapability;

    /** @var CommandBus */
    private $commandBus;

    /** @var SerializerInterface */
    private $serializer;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (!in_array('ROLE_ADMIN', $this->tokenStorage->getToken()->getRoleNames())) {
            throw new UnauthorizedHttpException('Basic');
        }

        $requestPayload = \json_decode($request->getContent(), true);
        $this->validateRequestPayloadScope(['name', 'preparation_time', 'difficulty', 'is_vegetarian'], $requestPayload);

        $command = new RegisterNewRecipe(
            $requestPayload['name'],
            $requestPayload['preparation_time'],
            $requestPayload['difficulty'],
            $requestPayload['is_vegetarian']
        );

        $recipe = $this->commandBus->handle($command);
        $serialized = $this->serializer->serialize($recipe, 'json');

        return new JsonResponse($serialized, JsonResponse::HTTP_CREATED, ['Content-Type' => 'application/json'], true);
    }
}
