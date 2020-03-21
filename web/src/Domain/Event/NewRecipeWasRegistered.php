<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Event;

use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Name;
use HelloFresh\Domain\PreparationTime;
use HelloFresh\Domain\RecipeId;

final class NewRecipeWasRegistered
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

    public static function with(array $payload): NewRecipeWasRegistered
    {
        $instance = new self();
        $instance->recipeId = $payload['recipe_id'];
        $instance->name = $payload['name'];
        $instance->preparationTime = $payload['preparation_time'];
        $instance->difficulty = $payload['difficulty'];
        $instance->isVegetarian = $payload['is_vegetarian'];

        return $instance;
    }

    public static function from(array $payload): NewRecipeWasRegistered
    {
        $instance = new self();
        $instance->recipeId = RecipeId::fromString($payload['recipe_id']);
        $instance->name = Name::fromString($payload['name']);
        $instance->preparationTime = PreparationTime::fromInteger($payload['preparation_time']);
        $instance->difficulty = Difficulty::fromInteger($payload['difficulty']);
        $instance->isVegetarian = $payload['is_vegetarian'];

        return $instance;
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function preparationTime(): PreparationTime
    {
        return $this->preparationTime;
    }

    public function difficulty(): Difficulty
    {
        return $this->difficulty;
    }

    public function isVegetarian(): bool
    {
        return $this->isVegetarian;
    }
}
