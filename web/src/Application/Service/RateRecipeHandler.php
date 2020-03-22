<?php
declare(strict_types=1);

namespace HelloFresh\Application\Service;

use HelloFresh\Domain\Command\RateRecipe;
use HelloFresh\Domain\Rate;
use HelloFresh\Domain\Service\RecipeService;

final class RateRecipeHandler
{
    /** @var RecipeService */
    private $service;

    public function __construct(RecipeService $service)
    {
        $this->service = $service;
    }

    public function handle(RateRecipe $command): Rate
    {
        return $this->service->rate($command);
    }
}
