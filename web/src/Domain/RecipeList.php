<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

final class RecipeList implements \JsonSerializable
{
    /** @var Recipe[]  */
    private $recipes = [];

    public function addRecipe(Recipe $recipe): void
    {
        $this->recipes[] = $recipe;
    }

    public function jsonSerialize(): array
    {
        return $this->recipes;
    }
}
