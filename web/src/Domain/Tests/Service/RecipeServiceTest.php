<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests\Service;

use HelloFresh\Domain\Command\DeleteRecipe;
use HelloFresh\Domain\Command\RateRecipe;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use HelloFresh\Domain\Command\UpdateRecipe;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeId;
use HelloFresh\Domain\Repository\RecipeRepository;
use HelloFresh\Domain\Service\RecipeService;
use PHPUnit\Framework\TestCase;

final class RecipeServiceTest extends TestCase
{
    public function testCanRegisterNewRecipe(): void
    {
        $recipeRepository = $this->createMock(RecipeRepository::class);
        $recipeRepository->expects($this->once())->method('save');

        $service = new RecipeService($recipeRepository);
        $command = new RegisterNewRecipe('Herby Pan-Seared Chicken', 30, 2, false);
        $recipe = $service->register($command);

        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertInstanceOf(RecipeId::class, $recipe->getRecipeId());
        $this->assertEquals($recipe->getName()->__toString(), $command->getName());
        $this->assertEquals($recipe->getDifficulty()->toInteger(), $command->getDifficulty());
        $this->assertEquals($recipe->getPreparationTime()->toMinutesInteger(), $command->getPreparationTime());
        $this->assertEquals($recipe->isVegetarian(), $command->isVegetarian());
    }

    public function testCanUpdateExistingRecipe(): void
    {
        $recipeId = RecipeId::generate();
        $recipeRepository = $this->createMock(RecipeRepository::class);
        $recipeRepository->expects($this->once())->method('save');
        $recipeRepository
            ->expects($this->once())
            ->method('loadById')
            ->willReturn([
                'recipe_id' => $recipeId->__toString(),
                'name' => 'Herby Pan-Seared Chicken',
                'preparation_time' => 30,
                'difficulty' => 2,
                'is_vegetarian' => false,
                'rate' => 0.0,
            ]);

        $service = new RecipeService($recipeRepository);
        $command = new UpdateRecipe($recipeId->__toString(), 'Herby Pan-Seared Chicken', 32, 2, false);
        $recipe = $service->update($command);

        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertEquals($recipe->getRecipeId()->__toString(), $command->getRecipeId());
        $this->assertEquals($recipe->getName()->__toString(), $command->getName());
        $this->assertEquals($recipe->getDifficulty()->toInteger(), $command->getDifficulty());
        $this->assertEquals($recipe->getPreparationTime()->toMinutesInteger(), $command->getPreparationTime());
        $this->assertEquals($recipe->isVegetarian(), $command->isVegetarian());
    }

    public function testCanDeleteExistingRecipe(): void
    {
        $recipeId = RecipeId::generate();
        $recipeRepository = $this->createMock(RecipeRepository::class);
        $recipeRepository->expects($this->once())->method('delete');
        $recipeRepository
            ->expects($this->once())
            ->method('loadById')
            ->willReturn([
                'recipe_id' => $recipeId->__toString(),
                'name' => 'Herby Pan-Seared Chicken',
                'preparation_time' => 30,
                'difficulty' => 2,
                'is_vegetarian' => false,
                'rate' => 0,
            ]);
        $service = new RecipeService($recipeRepository);
        $command = new DeleteRecipe($recipeId->__toString());
        $service->delete($command);
    }

    public function testCanRateRecipe(): void
    {
        $recipeId = RecipeId::generate();
        $recipeRepository = $this->createMock(RecipeRepository::class);
        $recipeRepository->expects($this->once())->method('rate');
        $recipeRepository
            ->expects($this->once())
            ->method('loadById')
            ->willReturn([
                'recipe_id' => $recipeId->__toString(),
                'name' => 'Herby Pan-Seared Chicken',
                'preparation_time' => 30,
                'difficulty' => 2,
                'is_vegetarian' => false,
                'rate' => 3,
            ]);
        $recipeRepository
            ->expects($this->once())
            ->method('loadRateByRecipeId')
            ->willReturn([
                'rate' => 3,
            ]);
        $service = new RecipeService($recipeRepository);
        $command = new RateRecipe($recipeId->__toString(), 4);
        $service->rate($command);
    }
}
