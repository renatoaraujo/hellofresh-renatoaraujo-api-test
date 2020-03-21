<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\Event\NewRecipeWasRegistered;
use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Event\RecipeWasDeleted;
use HelloFresh\Domain\Event\RecipeWasUpdated;
use HelloFresh\Domain\Name;
use HelloFresh\Domain\PreparationTime;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeId;
use PHPUnit\Framework\TestCase;

final class RecipeTest extends TestCase
{
    public function testRecipeCanRecordNewRecipeWasRegisteredEvent(): void
    {
        $recipe = Recipe::fromNewRecipeRegistration(
            Name::fromString('Herby Pan-Seared Chicken'),
            PreparationTime::fromInteger(30),
            Difficulty::fromInteger(2),
            false
        );

        $this->assertInstanceOf(Recipe::class, $recipe);

        $recordedEvents = $recipe->getRecordedEvents();
        $this->assertIsArray($recordedEvents);
        $this->assertArrayHasKey(0, $recordedEvents);
        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(NewRecipeWasRegistered::class, $recordedEvents[0]);

        /** @var NewRecipeWasRegistered $event */
        $event = $recordedEvents[0];

        $this->assertSame($event->recipeId(), $recipe->getRecipeId());
        $this->assertSame($event->name(), $recipe->getName());
        $this->assertSame($event->preparationTime(), $recipe->getPreparationTime());
        $this->assertSame($event->difficulty(), $recipe->getDifficulty());
        $this->assertSame($event->isVegetarian(), $recipe->isVegetarian());

        $recipe->clearRecordedEvents();
        $emptyRecordedEvents = $recipe->getRecordedEvents();
        $this->assertCount(0, $emptyRecordedEvents);
    }

    public function testCanCreateRecipeFromNewRecipeWasRegisteredEvent(): void
    {
        $event = NewRecipeWasRegistered::with([
            'recipe_id' => RecipeId::generate(),
            'name' => Name::fromString('Herby Pan-Seared Chicken'),
            'preparation_time' => PreparationTime::fromInteger(30),
            'difficulty' => Difficulty::fromInteger(2),
            'is_vegetarian' => false,
        ]);

        $recipe = Recipe::fromEvent($event);

        $this->assertSame($event->recipeId(), $recipe->getRecipeId());
        $this->assertSame($event->name(), $recipe->getName());
        $this->assertSame($event->preparationTime(), $recipe->getPreparationTime());
        $this->assertSame($event->difficulty(), $recipe->getDifficulty());
        $this->assertSame($event->isVegetarian(), $recipe->isVegetarian());

        $recordedEvents = $recipe->getRecordedEvents();
        $this->assertCount(0, $recordedEvents);
    }

    public function testRecipeCanRecordRecipeWasUpdatedEvent(): void
    {
        $recipeId = RecipeId::generate();
        $recipe = Recipe::fromPayload([
            'recipe_id' => $recipeId,
            'name' => Name::fromString('Herby Pan-Seared Chicken'),
            'preparation_time' => PreparationTime::fromInteger(30),
            'difficulty' => Difficulty::fromInteger(2),
            'is_vegetarian' => false,
            'rate' => 0,
        ]);

        $this->assertInstanceOf(Recipe::class, $recipe);

        $recipe->update(
            Name::fromString('Herby Pan-Seared Chicken'),
            PreparationTime::fromInteger(32),
            Difficulty::fromInteger(3),
            false
        );

        $recordedEvents = $recipe->getRecordedEvents();
        $this->assertIsArray($recordedEvents);
        $this->assertArrayHasKey(0, $recordedEvents);
        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(RecipeWasUpdated::class, $recordedEvents[0]);

        /** @var RecipeWasUpdated $event */
        $event = $recordedEvents[0];

        $this->assertSame($event->recipeId(), $recipe->getRecipeId());
        $this->assertSame($event->name(), $recipe->getName());
        $this->assertSame($event->preparationTime(), $recipe->getPreparationTime());
        $this->assertSame($event->difficulty(), $recipe->getDifficulty());
        $this->assertSame($event->isVegetarian(), $recipe->isVegetarian());

        $recipe->clearRecordedEvents();
        $emptyRecordedEvents = $recipe->getRecordedEvents();
        $this->assertCount(0, $emptyRecordedEvents);
    }

    public function testCanUpdateRecipeFromRecipeWasUpdatedEvent(): void
    {
        $event = RecipeWasUpdated::with([
            'recipe_id' => RecipeId::generate(),
            'name' => Name::fromString('Herby Pan-Seared Chicken'),
            'preparation_time' => PreparationTime::fromInteger(30),
            'difficulty' => Difficulty::fromInteger(2),
            'is_vegetarian' => false,
        ]);

        $recipe = Recipe::fromEvent($event);

        $this->assertSame($event->recipeId(), $recipe->getRecipeId());
        $this->assertSame($event->name(), $recipe->getName());
        $this->assertSame($event->preparationTime(), $recipe->getPreparationTime());
        $this->assertSame($event->difficulty(), $recipe->getDifficulty());
        $this->assertSame($event->isVegetarian(), $recipe->isVegetarian());

        $recordedEvents = $recipe->getRecordedEvents();
        $this->assertCount(0, $recordedEvents);
    }

    public function testRecipeCanRecordRecipeWasDeletedEvent(): void
    {
        $recipeId = RecipeId::generate();
        $recipe = Recipe::fromPayload([
            'recipe_id' => $recipeId,
            'name' => Name::fromString('Herby Pan-Seared Chicken'),
            'preparation_time' => PreparationTime::fromInteger(30),
            'difficulty' => Difficulty::fromInteger(2),
            'is_vegetarian' => false,
            'rate' => 0,
        ]);

        $this->assertInstanceOf(Recipe::class, $recipe);

        $recipe->delete();

        $recordedEvents = $recipe->getRecordedEvents();
        $this->assertIsArray($recordedEvents);
        $this->assertArrayHasKey(0, $recordedEvents);
        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(RecipeWasDeleted::class, $recordedEvents[0]);

        $recipe->clearRecordedEvents();
        $emptyRecordedEvents = $recipe->getRecordedEvents();
        $this->assertCount(0, $emptyRecordedEvents);
    }
}
