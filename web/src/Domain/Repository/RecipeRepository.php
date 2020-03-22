<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Repository;

use HelloFresh\Domain\Recipe;

interface RecipeRepository
{
    public function save(Recipe $recipe): void;
}
