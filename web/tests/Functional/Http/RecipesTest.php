<?php
declare(strict_types=1);

namespace Tests\Functional\Http;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;

/**
 * PLEASE READ IT (before punching me :D) --------------------
 * This is a basic functional tests I did in a dirty and quick way to make sure everything works,
 * and because I am not using any kind of framework to deal with database easily I will add a
 * certain order of the tests to be sure that I can test the API and it's working.
 *
 * In the real life I would never do this, instead I would be using fixtures or specific database
 * for testing being able to start and destroy every dependency on the beginning and at the end of
 * the execution.
 * ------------------------------------------------------------
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
        $response = $this->http->request('POST', 'recipes', [
            'body' => \json_encode([
                "name" => "Herby Pan-Seared Chicken",
                "preparation_time" => 30,
                "difficulty" => 2,
                "is_vegetarian" => true
            ])
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $registeredRecipe = json_decode($response->getBody()->getContents(), true);
        $recipeId = $registeredRecipe['recipe_id'];
        $this->assertTrue(Uuid::isValid($recipeId));

        return $recipeId;
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
}
