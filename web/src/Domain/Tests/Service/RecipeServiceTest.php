<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests\Service;

use HelloFresh\Domain\Command\RegisterNewRecipe;
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
}
