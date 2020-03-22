<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Service;

use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Name;
use HelloFresh\Domain\PreparationTime;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\Repository\RecipeRepository;

final class RecipeService
{
    /** @var RecipeRepository */
    private $repository;

    public function __construct(RecipeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(RegisterNewRecipe $command): Recipe
    {
        $recipe = Recipe::fromNewRecipeRegistration(
            Name::fromString($command->getName()),
            PreparationTime::fromInteger($command->getPreparationTime()),
            Difficulty::fromInteger($command->getDifficulty()),
            $command->isVegetarian()
        );

        $this->repository->save($recipe);

        return $recipe;
    }
}
