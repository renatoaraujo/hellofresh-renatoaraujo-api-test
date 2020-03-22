<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\DeleteRecipe;
use HelloFresh\Domain\Service\RecipeService;

final class DeleteRecipeHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(DeleteRecipe $command)
    {
        $this->service->delete($command);
    }
}
