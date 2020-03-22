<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Service;

use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Name;
use HelloFresh\Domain\PreparationTime;
use HelloFresh\Domain\Rate;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeId;
use HelloFresh\Domain\RecipeList;
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

    public function list(ListRecipes $command): RecipeList
    {
        $recipeList = new RecipeList();
        $recipes = $this->repository->load($command->getCriteria());

        if (!empty($recipes)) {
            foreach ($recipes as $recipePayload) {
                $recipeList->addRecipe($this->getFromPayload($recipePayload));
            }
        }

        return $recipeList;
    }

    private function getFromPayload(array $payload): Recipe
    {
        $payload['recipe_id'] = RecipeId::fromString($payload['recipe_id']);
        $payload['name'] = Name::fromString($payload['name']);
        $payload['preparation_time'] = PreparationTime::fromInteger($payload['preparation_time']);
        $payload['difficulty'] = Difficulty::fromInteger($payload['difficulty']);
        $payload['rate'] = Rate::fromEmptyRate();

        if ($payload['rate'] > 1) {
            $payload['rate'] = Rate::fromFloat((float) $payload['rate']);
        }

        return Recipe::fromPayload($payload);
    }
}
