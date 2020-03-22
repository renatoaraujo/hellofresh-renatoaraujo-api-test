<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use HelloFresh\Domain\Command\ListRecipes as ListRecipesCommand;

final class ListRecipes
{
    /** @var CommandBus */
    private $commandBus;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(CommandBus $commandBus, SerializerInterface $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (null === $criteria = \json_decode($request->getContent(), true)) {
            $criteria = [];
        }

        $command = new ListRecipesCommand($criteria);

        $recipes = $this->commandBus->handle($command);
        $serialized = $this->serializer->serialize($recipes, 'json');

        return new JsonResponse($serialized, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json'], true);
    }
}
