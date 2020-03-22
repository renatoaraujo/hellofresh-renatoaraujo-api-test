<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use HelloFresh\Domain\Command\DeleteRecipe as DeleteRecipeCommand;

final class DeleteRecipe
{
    /** @var CommandBus */
    private $commandBus;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(CommandBus $commandBus, TokenStorageInterface $tokenStorage)
    {
        $this->commandBus = $commandBus;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (!in_array('ROLE_ADMIN', $this->tokenStorage->getToken()->getRoleNames())) {
            throw new UnauthorizedHttpException('Basic');
        }

        $command = new DeleteRecipeCommand(
            $request->get('id')
        );

        $this->commandBus->handle($command);
        return new JsonResponse([], JsonResponse::HTTP_OK);
    }
}
