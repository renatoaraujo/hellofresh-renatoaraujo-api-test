<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use HelloFresh\Application\Controller\Utils\PayloadCapability;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class CreateRecipe
{
    use PayloadCapability;

    /** @var CommandBus */
    private $commandBus;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        CommandBus $commandBus,
        SerializerInterface $serializer
    ) {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
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
