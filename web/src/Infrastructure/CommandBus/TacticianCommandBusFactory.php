<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\CommandBus;

use HelloFresh\Application\Service\CreateRecipeHandler;
use HelloFresh\Application\Service\ListRecipesHandler;
use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\Service\RecipeService;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use League\Tactician\CommandBus;
use League\Tactician\Setup\QuickStart;

final class TacticianCommandBusFactory
{
    public static function build(RecipeService $service): CommandBus
    {
        return QuickStart::create([
            RegisterNewRecipe::class => new CreateRecipeHandler($service),
            ListRecipes::class => new ListRecipesHandler($service),
        ]);
    }
}
