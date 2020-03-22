<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\CommandBus;

use HelloFresh\Application\Service\CreateRecipeHandler;
use HelloFresh\Application\Service\DeleteRecipeHandler;
use HelloFresh\Application\Service\ListRecipesHandler;
use HelloFresh\Application\Service\RateRecipeHandler;
use HelloFresh\Application\Service\ReadRecipeHandler;
use HelloFresh\Application\Service\UpdateRecipeHandler;
use HelloFresh\Domain\Command\DeleteRecipe;
use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\Command\RateRecipe;
use HelloFresh\Domain\Command\UpdateRecipe;
use HelloFresh\Domain\Command\ViewRecipe;
use HelloFresh\Domain\Service\RecipeService;
use HelloFresh\Domain\Command\RegisterNewRecipe;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;

final class TacticianCommandBusFactory
{
    public static function build(RecipeService $service): CommandBus
    {
        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator([
                RegisterNewRecipe::class => new CreateRecipeHandler($service),
                ListRecipes::class => new ListRecipesHandler($service),
                ViewRecipe::class => new ReadRecipeHandler($service),
                UpdateRecipe::class => new UpdateRecipeHandler($service),
                DeleteRecipe::class => new DeleteRecipeHandler($service),
                RateRecipe::class => new RateRecipeHandler($service),
            ]),
            new HandleInflector()
        );

        $lockingMiddleware = new LockingMiddleware();

        return new CommandBus([$lockingMiddleware, $handlerMiddleware]);
    }
}
