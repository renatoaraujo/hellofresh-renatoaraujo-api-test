<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Service;

use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Name;
use HelloFresh\Domain\PreparationTime;
use HelloFresh\Domain\Recipe;

final class RecipeService
{
    public function register(RegisterNewRecipe $command): Recipe
    {
        return Recipe::fromNewRecipeRegistration(
            Name::fromString($command->getName()),
            PreparationTime::fromInteger($command->getPreparationTime()),
            Difficulty::fromInteger($command->getDifficulty()),
            $command->isVegetarian()
        );
    }
}
