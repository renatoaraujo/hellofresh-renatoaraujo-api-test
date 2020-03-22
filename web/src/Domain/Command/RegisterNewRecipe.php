<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Command;

final class RegisterNewRecipe
{
    /** @var string */
    private $name;

    /** @var integer */
    private $preparationTime;

    /** @var integer */
    private $difficulty;

    /** @var boolean */
    private $isVegetarian;

    public function __construct(string $name, int $preparationTime, int $difficulty, bool $isVegetarian)
    {
        $this->name = $name;
        $this->preparationTime = $preparationTime;
        $this->difficulty = $difficulty;
        $this->isVegetarian = $isVegetarian;
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
