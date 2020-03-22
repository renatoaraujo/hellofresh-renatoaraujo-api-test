<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use HelloFresh\Domain\Command\DeleteRecipe as DeleteRecipeCommand;

final class DeleteRecipe
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $command = new DeleteRecipeCommand(
            $request->get('id')
        );

        $this->commandBus->handle($command);
        return new JsonResponse([], JsonResponse::HTTP_OK);
    }
}
