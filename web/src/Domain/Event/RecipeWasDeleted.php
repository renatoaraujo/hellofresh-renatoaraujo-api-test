<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Event;

use HelloFresh\Domain\RecipeId;

final class RecipeWasDeleted
{
    /** @var RecipeId */
    private $recipeId;

    public static function with(array $payload): RecipeWasDeleted
    {
        $instance = new self();
        $instance->recipeId = $payload['recipe_id'];

        return $instance;
    }

    public static function from(array $payload): RecipeWasDeleted
    {
        $instance = new self();
        $instance->recipeId = RecipeId::fromString($payload['recipe_id']);

        return $instance;
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }
}
