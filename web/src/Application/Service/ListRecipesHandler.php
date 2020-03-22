<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\ListRecipes;
use HelloFresh\Domain\RecipeList;
use HelloFresh\Domain\Service\RecipeService;

final class ListRecipesHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(ListRecipes $command): RecipeList
    {
        return $this->service->list($command);
    }
}
