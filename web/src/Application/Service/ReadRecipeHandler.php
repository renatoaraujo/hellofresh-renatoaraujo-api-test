<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\ViewRecipe;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\Service\RecipeService;

final class ReadRecipeHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(ViewRecipe $command): Recipe
    {
        return $this->service->load($command);
    }
}
