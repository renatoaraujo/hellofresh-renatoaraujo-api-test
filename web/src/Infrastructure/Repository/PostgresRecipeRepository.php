<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Repository;

use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\Repository\RecipeRepository;
use PDO;

final class PostgresRecipeRepository implements RecipeRepository
{
    /** @var PDO */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Recipe $recipe): void
    {
        $row = [
            ':recipe_id' => $recipe->getRecipeId()->__toString(),
            ':data' => json_encode([
                'name' => $recipe->getName()->__toString(),
                'preparation_time' => $recipe->getPreparationTime()->toMinutesInteger(),
                'difficulty' => $recipe->getDifficulty()->toInteger(),
                'is_vegetarian' => $recipe->isVegetarian(),
            ]),
        ];

        $statement = $this->connection->prepare('
          INSERT INTO 
            recipe (
              recipe_id, 
              data
            ) 
          VALUES (
            :recipe_id, 
            :data
          ) 
          ON CONFLICT (recipe_id) DO UPDATE SET data = :data
        ');
        $statement->execute($row);
    }
}
