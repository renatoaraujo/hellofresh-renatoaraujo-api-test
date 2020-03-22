<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\UpdateRecipe;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\Service\RecipeService;

final class UpdateRecipeHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(UpdateRecipe $command): Recipe
    {
        return $this->service->update($command);
    }
}
