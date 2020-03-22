<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Repository\Exception;

final class RecipeNotFoundException extends \Exception
{
    public static function withRecipeId(string $id): RecipeNotFoundException
    {
        return new self(
            sprintf('Recipe "%s" not found.', $id)
        );
    }
}
