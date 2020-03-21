<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

final class Recipe
{
    /** @var RecipeId */
    private $recipeId;

    /** @var Name */
    private $name;

    /** @var PreparationTime */
    private $preparationTime;

    /** @var Difficulty */
    private $difficulty;

    /** @var boolean */
    private $isVegetarian;

    private function __construct()
    {
    }

    public function getRecipeId(): RecipeId
    {
        return $this->recipeId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPreparationTime(): PreparationTime
    {
        return $this->preparationTime;
    }

    public function getDifficulty(): Difficulty
    {
        return $this->difficulty;
    }

    public function isVegetarian(): bool
    {
        return $this->isVegetarian;
    }
}
