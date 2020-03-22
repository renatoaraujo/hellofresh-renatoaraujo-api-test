<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteRecipe
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(["Hello, Fresh!"], 200);
    }
}
