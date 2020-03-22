<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Command;

final class RateRecipe
{
    /** @var string */
    private $recipeId;

    /** @var float */
    private $rate;

    public function __construct(string $recipeId, float $rate)
    {
        $this->recipeId = $recipeId;
        $this->rate = $rate;
    }

    public function getRecipeId(): string
    {
        return $this->recipeId;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
