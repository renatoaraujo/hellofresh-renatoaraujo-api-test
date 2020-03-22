<?php
declare(strict_types=1);

namespace HelloFresh\Application\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateRecipe
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(["Hello, Fresh!"], 200);
    }
}
