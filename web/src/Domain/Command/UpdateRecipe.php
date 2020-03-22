<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Command;

final class UpdateRecipe
{
    /** @var string */
    private $recipeId;

    /** @var string */
    private $name;

    /** @var integer */
    private $preparationTime;

    /** @var integer */
    private $difficulty;

    /** @var boolean */
    private $isVegetarian;

    public function __construct(
        string $recipeId,
        string $name,
        int $preparationTime,
        int $difficulty,
        bool $isVegetarian
    ) {
        $this->recipeId = $recipeId;
        $this->name = $name;
        $this->preparationTime = $preparationTime;
        $this->difficulty = $difficulty;
        $this->isVegetarian = $isVegetarian;
    }

    public function getRecipeId(): string
    {
        return $this->recipeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPreparationTime(): int
    {
        return $this->preparationTime;
    }

    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    public function isVegetarian(): bool
    {
        return $this->isVegetarian;
    }
}
