<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use HelloFresh\Domain\Command\RateRecipe as RateRecipeCommand;
use HelloFresh\Application\Controller\Utils\PayloadCapability;

final class RateRecipe
{
    use PayloadCapability;

    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $requestPayload = \json_decode($request->getContent(), true);
        $this->validateRequestPayloadScope(['rate'], $requestPayload);

        $command = new RateRecipeCommand(
            $request->get('id'),
            $requestPayload['rate']
        );

        $rate = $this->commandBus->handle($command);
        return new JsonResponse($rate, JsonResponse::HTTP_CREATED);
    }
}
