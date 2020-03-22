<?php
declare(strict_types=1);

namespace HelloFresh\Infrastructure\Repository;

use HelloFresh\Domain\Rate;
use HelloFresh\Domain\Recipe;
use HelloFresh\Domain\RecipeId;
use HelloFresh\Domain\Repository\RecipeRepository;
use HelloFresh\Infrastructure\Repository\Exception\RecipeNotFoundException;
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
          recipe.recipe_id as recipe_id,
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
            array_walk($criteria, function ($value, $column) use (&$clauses) {
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

        foreach ($records as $key => $record) {
            $loadedRecipes[$key] = \json_decode($record['data'], true);
            $loadedRecipes[$key]['recipe_id'] = $record['recipe_id'];
            $loadedRecipes[$key]['rate'] = $record['rate'];
        }

        return $loadedRecipes;
    }

    public function loadById(RecipeId $recipeId): array
    {
        $query = 'SELECT
          recipe.recipe_id as recipe_id,
          recipe.data as data,
          coalesce(avg(value), 0) as rate
        FROM
          recipe LEFT JOIN
          rate ON rate.recipe_id = recipe.recipe_id
        WHERE 
          recipe.recipe_id = :recipe_id
        GROUP BY
          recipe.recipe_id,
          rate.recipe_id';

        $statement = $this->connection->prepare($query);
        $statement->execute([':recipe_id' => $recipeId->__toString()]);
        $record = $statement->fetch(\PDO::FETCH_ASSOC);

        if (empty($record)) {
            throw RecipeNotFoundException::withRecipeId($recipeId->__toString());
        }

        $payload = \json_decode($record['data'], true);
        $payload['recipe_id'] = $record['recipe_id'];
        $payload['rate'] = $record['rate'];

        return $payload;
    }

    public function delete(Recipe $recipe): void
    {
        $statement = $this->connection->prepare('DELETE FROM recipe WHERE recipe_id = :recipe_id');
        $statement->execute(['recipe_id' => $recipe->getRecipeId()->__toString()]);
    }

    public function rate(Recipe $recipe, Rate $rate): void
    {
        $row = [
            ':recipe_id' => $recipe->getRecipeId()->__toString(),
            ':rate' => $rate->toFloat(),
        ];

        $statement = $this->connection->prepare('
          INSERT INTO 
            rate (
              recipe_id, 
              value
            ) 
          VALUES (
            :recipe_id, 
            :rate
          )
        ');
        $statement->execute($row);
    }

    public function loadRateByRecipeId(RecipeId $recipeId): array
    {
        $query = 'SELECT AVG(value) AS rate FROM rate WHERE recipe_id = :recipe_id GROUP BY recipe_id';

        $statement = $this->connection->prepare($query);
        $statement->execute([':recipe_id' => $recipeId->__toString()]);
        $record = $statement->fetch(\PDO::FETCH_ASSOC);

        $payload = ['rate' => $record['rate']];

        return $payload;
    }
}
