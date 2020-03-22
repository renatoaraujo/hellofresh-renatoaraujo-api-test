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

    public function load(array $criteria = []): array
    {
        $query = 'SELECT
          recipe.recipe_id as id,
          recipe.data as data,
          coalesce(avg(value), 0) as rate
        FROM
          recipe LEFT JOIN
          rate ON rate.recipe_id = recipe.recipe_id
        %s
        GROUP BY
          recipe.recipe_id,
          rate.recipe_id
        ';

        $where = '';

        if (!empty($criteria)) {
            $clauses = [];
            array_walk($criteria, function($value, $column) use (&$clauses) {
                if ('name' != $column) {
                    return;
                }
                $clauses[] = sprintf('data->>\'%s\' ILIKE \'%%%s%%\'', $column, $value);
            });

            $where = 'WHERE ' . implode(' AND ', $clauses);
        }

        $query = sprintf($query, $where);

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $records = $statement->fetchAll();

        $loadedRecipes = [];

        foreach($records as $key => $record) {
            $loadedRecipes[$key] = \json_decode($record['data'], true);
            $loadedRecipes[$key]['recipe_id'] = $record['id'];
            $loadedRecipes[$key]['rate'] = $record['rate'];
        }

        return $loadedRecipes;
    }
}
