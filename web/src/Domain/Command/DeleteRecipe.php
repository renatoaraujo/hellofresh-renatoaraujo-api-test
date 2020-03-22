<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Command;

final class DeleteRecipe
{
    /** @var string */
    private $recipeId;

    public function __construct(string $recipeId)
    {
        $this->recipeId = $recipeId;
    }

    public function getRecipeId(): string
    {
        return $this->recipeId;
    }
}
