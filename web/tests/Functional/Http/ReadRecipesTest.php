<?php
declare(strict_types=1);

namespace Tests\Functional\Http;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ReadRecipesTest extends TestCase
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
        $this->markTestIncomplete();
        $response = $this->http->request('GET', 'recipes');

        $this->assertEquals(200, $response->getStatusCode());
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }
}
