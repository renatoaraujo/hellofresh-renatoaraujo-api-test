<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Event;

use HelloFresh\Domain\Rate;
use HelloFresh\Domain\RecipeId;

final class RecipeWasRated
{
    /** @var RecipeId */
    private $recipeId;

    /** @var Rate */
    private $rate;

    public static function with(array $payload): RecipeWasRated
    {
        $instance = new self();
        $instance->recipeId = $payload['recipe_id'];
        $instance->rate = $payload['rate'];

        return $instance;
    }

    public static function from(array $payload): RecipeWasRated
    {
        $instance = new self();
        $instance->recipeId = RecipeId::fromString($payload['recipe_id']);
        $instance->rate = Rate::fromInteger($payload['rate']);

        return $instance;
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }

    public function rate(): Rate
    {
        return $this->rate;
    }
}
