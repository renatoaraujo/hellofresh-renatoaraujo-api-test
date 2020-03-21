<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Event\NewRecipeWasRegistered;

final class Recipe
{
    use RecordEventCapability;

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

    public static function fromEvent($event): Recipe
    {
        $recipe = new self();

        if ($event instanceof NewRecipeWasRegistered) {
            $recipe->applyNewRecipeWasRegistered($event);
        }

        return $recipe;
    }

    public static function fromNewRecipeRegistration(
        Name $name,
        PreparationTime $preparationTime,
        Difficulty $difficulty,
        bool $isVegetarian
    ): Recipe
    {
        $instance = new self();

        $recipeId = RecipeId::generate();
        $instance->record(NewRecipeWasRegistered::with([
            'recipe_id' => $recipeId,
            'name' => $name,
            'preparation_time' => $preparationTime,
            'difficulty' => $difficulty,
            'is_vegetarian' => $isVegetarian,
        ]));

        return $instance;
    }

    protected function applyNewRecipeWasRegistered(NewRecipeWasRegistered $event): void
    {
        $this->recipeId = $event->recipeId();
        $this->name = $event->name();
        $this->preparationTime = $event->preparationTime();
        $this->difficulty = $event->difficulty();
        $this->isVegetarian = $event->isVegetarian();
    }
}
