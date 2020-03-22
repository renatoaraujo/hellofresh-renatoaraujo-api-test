<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\Service\RecipeService;

final class CreateRecipeHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(RegisterNewRecipe $command): Recipe
    {
        return $this->service->register($command);
    }
}
