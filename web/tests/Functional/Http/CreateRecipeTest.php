<?php
declare(strict_types=1);

namespace Tests\Functional\Http;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class CreateRecipeTest extends TestCase
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

    public function testListRecipesWithSuccessfulRequest(): void
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
    }
}
