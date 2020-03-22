<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Service;

use HelloFresh\Domain\Command\DeleteRecipe;
use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\Command\RateRecipe;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Command\UpdateRecipe;
use HelloFresh\Domain\Command\ViewRecipe;
use HelloFresh\Domain\Rate;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeList;
use HelloFresh\Domain\Repository\RecipeRepository;

final class RecipeService
{
    /** @var RecipeRepository */
    private $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function register(RegisterNewRecipe $command): Recipe
    {
    }

    public function update(UpdateRecipe $command): Recipe
    {
    }

    public function delete(DeleteRecipe $command): void
    {
    }

    public function load(ViewRecipe $command): Recipe
    {
    }

    public function list(ListRecipes $command): RecipeList
    {
    }

    public function rate(RateRecipe $command): Rate
    {
    }
}
