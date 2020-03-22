<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Repository;

use HelloFresh\Domain\Rate;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeId;

interface RecipeRepository
{
    public function save(Recipe $recipe): void;

    public function load(array $criteria = []): array;

    public function loadById(RecipeId $recipeId): array;

    public function delete(Recipe $recipe): void;

    public function rate(Recipe $recipe, Rate $rate): void;

    public function loadRateByRecipeId(RecipeId $recipeId): array;
}
