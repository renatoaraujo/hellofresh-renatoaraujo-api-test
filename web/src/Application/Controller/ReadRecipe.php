<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use HelloFresh\Domain\Command\ViewRecipe;

final class ReadRecipe
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
        try {
            $command = new ViewRecipe($request->get('id'));
            $recipe = $this->commandBus->handle($command);
            $serialized = $this->serializer->serialize($recipe, 'json');
            $statusCode = JsonResponse::HTTP_OK;

            return new JsonResponse($serialized, $statusCode, ['Content-Type' => 'application/json'], true);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
