<?php
declare(strict_types=1);

namespace Tests\Functional\Http;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;

/**
 * DISCLAIMER
 * ----------
 * This is a basic functional tests I did in a dirty and quick way to make sure everything works,
 * and because I am not using any kind of framework to deal with database easily I will add a
 * certain order of the tests to be sure that I can test the API and it's working.
 *
 * In the real life I would never do this, instead I would be using fixtures or specific database
 * for testing being able to start and destroy every dependency on the beginning and at the end of
 * the execution.
 *
 * Tests for real that I would be proud using in real life is inside of the Domain!
 * Check: web/src/Domain/Tests
 * ----------
 *
 * @package Tests\Functional\Http
 */
class RecipesTest extends TestCase
{
    /** @var Client */
    private $http;

    protected function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost/']);
    }

    protected function tearDown(): void
    {
        $this->http = null;
    }

    /**
     * @testdox Can create a new recipe with parameters
     * @return string
     */
    public function testCreateRecipeWithSuccessfulRequest(): string
    {
        $response = $this->http->request(
            'POST',
            'recipes',
            [
                'body' => \json_encode([
                    "name" => "Herby Pan-Seared Chicken",
                    "preparation_time" => 30,
                    "difficulty" => 2,
                    "is_vegetarian" => true
                ]),
                'auth' => [
                    'admin',
                    'admin'
                ]
            ]
        );

        $this->assertEquals(201, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $registeredRecipe = json_decode($response->getBody()->getContents(), true);
        $recipeId = $registeredRecipe['recipe_id'];
        $this->assertTrue(Uuid::isValid($recipeId));

        return $recipeId;
    }

    /**
     * @testdox Can't create a new recipe with unauthorized request
     */
    public function testCreateRecipeWithUnauthorizedRequest(): void
    {
        $response = $this->http->request(
            'POST',
            'recipes',
            [
                'body' => \json_encode([
                    "name" => "Herby Pan-Seared Chicken",
                    "preparation_time" => 30,
                    "difficulty" => 2,
                    "is_vegetarian" => true
                ]),
                'http_errors' => false
            ]
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @testdox Can list all registered recipes
     */
    public function testListRecipesWithSuccessfulRequest(): void
    {
        $response = $this->http->request('GET', 'recipes');

        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $recipesList = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($recipesList);
        $this->assertNotEmpty($recipesList);

        $firstRecipeIdFromList = $recipesList[0]['recipe_id'];
        $this->assertTrue(Uuid::isValid($firstRecipeIdFromList));
    }

    /**
     * @depends testCreateRecipeWithSuccessfulRequest
     * @testdox Can read recipe with registered ID
     * @param string $recipeId
     */
    public function testReadRecipeByIdWithSuccessfulRequest(string $recipeId): void
    {
        $response = $this->http->request(
            'GET',
            sprintf('recipes/%s', $recipeId)
        );

        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $recipe = json_decode($response->getBody()->getContents(), true);
        $this->assertIsArray($recipe);
        $this->assertSame($recipeId, $recipe['recipe_id']);
    }

    /**
     * @dataProvider invalidIdProvider
     * @testdox Can't find recipe with random or unregistered ID: $randomString
     * @param string $randomString
     */
    public function testNotFoundRecipeWithId(string $randomString)
    {
        $response = $this->http->request(
            'GET',
            sprintf('recipes/%s', $randomString),
            [
                'http_errors' => false
            ]
        );

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function invalidIdProvider(): array
    {
        return [
            [uniqid()], // Should be an error because it's not valid UUID
            [Uuid::uuid4()->toString()] // Should be an error because it does not exists in Database
        ];
    }

    /**
     * @depends testCreateRecipeWithSuccessfulRequest
     * @testdox Can update recipe by ID with valid payload
     * @param string $recipeId
     * @return string
     * @throws \Exception
     */
    public function testUpdateRecipeWithSuccessfulRequest(string $recipeId): string
    {
        $newPreparationTime = \random_int(10, 100);
        $newName = 'Herby Pan-Seared Chicken 2';
        $newDifficulty = \random_int(1, 3);
        $isVegetarian = false;

        $response = $this->http->request(
            'PUT',
            sprintf('recipes/%s', $recipeId),
            [
                'body' => \json_encode([
                    "name" => $newName,
                    "preparation_time" => $newPreparationTime,
                    "difficulty" => $newDifficulty,
                    "is_vegetarian" => $isVegetarian
                ]),
                'auth' => [
                    'admin',
                    'admin'
                ]
            ]
        );

        $this->assertEquals(201, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $updatedRecipe = json_decode($response->getBody()->getContents(), true);
        $recipeId = $updatedRecipe['recipe_id'];
        $this->assertTrue(Uuid::isValid($recipeId));
        $this->assertSame($updatedRecipe['name'], $newName);
        $this->assertSame($updatedRecipe['preparation_time'], $newPreparationTime);
        $this->assertSame($updatedRecipe['difficulty'], $newDifficulty);
        $this->assertSame($updatedRecipe['is_vegetarian'], $isVegetarian);

        return $recipeId;
    }

    /**
     * @depends testCreateRecipeWithSuccessfulRequest
     * @testdox Can update recipe by ID with valid payload
     * @param string $recipeId
     * @throws \Exception
     */
    public function testUpdateRecipeWithUnauthorizedRequest(string $recipeId): void
    {
        $response = $this->http->request(
            'PUT',
            sprintf('recipes/%s', $recipeId),
            [
                'body' => \json_encode([]),
                'http_errors' => false
            ]
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * @depends testUpdateRecipeWithSuccessfulRequest
     * @testdox Can rate recipe with registered ID
     * @param string $recipeId
     * @return string
     * @throws \Exception
     */
    public function testRateRecipeByIdWithSuccessfulRequest(string $recipeId): string
    {
        $ratingValue = \random_int(1, 5);
        $response = $this->http->request(
            'POST',
            sprintf('recipes/%s/rating', $recipeId),
            [
                'body' => \json_encode([
                    'rate' => $ratingValue
                ])
            ]
        );

        $this->assertEquals(201, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $rate = json_decode($response->getBody()->getContents(), true);
        $this->assertSame($rate['rate'], $ratingValue);

        return $recipeId;
    }

    /**
     * @depends testRateRecipeByIdWithSuccessfulRequest
     * @testdox Can deleted recipe with registered ID
     * @param string $recipeId
     */
    public function testDeleteRecipeByIdWithSuccessfulRequest(string $recipeId): void
    {
        $response = $this->http->request(
            'DELETE',
            sprintf('recipes/%s', $recipeId),
            [
                'auth' => [
                    'admin',
                    'admin'
                ]
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @testdox Can deleted recipe with registered ID
     */
    public function testDeleteRecipeByIdWithUnauthorizedRequest(): void
    {
        $response = $this->http->request(
            'DELETE',
            sprintf('recipes/%s', Uuid::uuid4()->toString()),
            [
                'http_errors' => false
            ]
        );

        $this->assertEquals(401, $response->getStatusCode());
    }
}
