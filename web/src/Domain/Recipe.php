<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Event\NewRecipeWasRegistered;
use HelloFresh\Domain\Event\RecipeWasDeleted;
use HelloFresh\Domain\Event\RecipeWasRated;
use HelloFresh\Domain\Event\RecipeWasUpdated;

final class Recipe implements \JsonSerializable
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

    /** @var Rate */
    private $rate;

    private function __construct()
    {
    }

    public static function fromNewRecipeRegistration(
        Name $name,
        PreparationTime $preparationTime,
        Difficulty $difficulty,
        bool $isVegetarian
    ): Recipe {
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

    public static function fromPayload(array $payload): Recipe
    {
        $instance = new self();
        $instance->recipeId = $payload['recipe_id'];
        $instance->name = $payload['name'];
        $instance->preparationTime = $payload['preparation_time'];
        $instance->difficulty = $payload['difficulty'];
        $instance->isVegetarian = $payload['is_vegetarian'];
        $instance->rate = $payload['rate'];

        return $instance;
    }

    public function update(
        Name $name,
        PreparationTime $preparationTime,
        Difficulty $difficulty,
        bool $isVegetarian
    ): void {
        $this->record(RecipeWasUpdated::with([
            'recipe_id' => $this->recipeId,
            'name' => $name,
            'preparation_time' => $preparationTime,
            'difficulty' => $difficulty,
            'is_vegetarian' => $isVegetarian,
        ]));
    }

    public function delete(): void
    {
        $this->record(RecipeWasDeleted::with([
            'recipe_id' => $this->recipeId,
        ]));
    }

    public function rate(Rate $rate): void
    {
        $this->record(RecipeWasRated::with([
            'recipe_id' => $this->recipeId,
            'rate' => $rate
        ]));
    }

    protected function applyNewRecipeWasRegistered(NewRecipeWasRegistered $event): void
    {
        $this->recipeId = $event->recipeId();
        $this->name = $event->name();
        $this->preparationTime = $event->preparationTime();
        $this->difficulty = $event->difficulty();
        $this->isVegetarian = $event->isVegetarian();
    }

    protected function applyRecipeWasUpdated(RecipeWasUpdated $event): void
    {
        $this->recipeId = $event->recipeId();
        $this->name = $event->name();
        $this->preparationTime = $event->preparationTime();
        $this->difficulty = $event->difficulty();
        $this->isVegetarian = $event->isVegetarian();
    }

    public static function fromEvent($event): Recipe
    {
        $recipe = new self();

        if ($event instanceof NewRecipeWasRegistered) {
            $recipe->applyNewRecipeWasRegistered($event);
        }

        if ($event instanceof RecipeWasUpdated) {
            $recipe->applyRecipeWasUpdated($event);
        }

        return $recipe;
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

    public function getRate(): Rate
    {
        if (null === $this->rate) {
            return Rate::fromFloat(Rate::VALUE_MIN);
        }

        return $this->rate;
    }

    public function jsonSerialize(): array
    {
        return [
            'recipe_id' => $this->getRecipeId()->__toString(),
            'name' => $this->getName()->__toString(),
            'preparation_time' => $this->getPreparationTime()->toMinutesInteger(),
            'difficulty' => $this->getDifficulty()->toInteger(),
            'is_vegetarian' => $this->isVegetarian(),
            'rate' => $this->getRate()->toFloat()
        ];
    }
}
