<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Service;

use HelloFresh\Domain\Command\DeleteRecipe;
use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Command\UpdateRecipe;
use HelloFresh\Domain\Command\ViewRecipe;
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
        $payload['rate'] = ($payload['rate'] > 1) ? Rate::fromFloat((float) $payload['rate']) : Rate::fromEmptyRate();

        return Recipe::fromPayload($payload);
    }

    public function load(ViewRecipe $command): Recipe
    {
        $payload = $this->repository->loadById(
            RecipeId::fromString($command->getRecipeId())
        );
        return $this->getFromPayload($payload);
    }

    public function update(UpdateRecipe $command): Recipe
    {
        $payload = $this->repository->loadById(
            RecipeId::fromString($command->getRecipeId())
        );
        $recipe = $this->getFromPayload($payload);

        $recipe->update(
            Name::fromString($command->getName()),
            PreparationTime::fromInteger($command->getPreparationTime()),
            Difficulty::fromInteger($command->getDifficulty()),
            $command->isVegetarian()
        );

        $this->repository->save($recipe);

        return $recipe;
    }

    public function delete(DeleteRecipe $command): void
    {
        $payload = $this->repository->loadById(
            RecipeId::fromString($command->getRecipeId())
        );

        $recipe = $this->getFromPayload($payload);
        $recipe->delete();

        $this->repository->delete($recipe);
    }
}
