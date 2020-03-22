<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\Exception\InvalidUuidStringException;
use HelloFresh\Domain\RecipeId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class RecipeIdTest extends TestCase
{
    /**
     * @testdox Can create RecipeId with valid UUID v4
     */
    public function testCanGenerateUuid(): void
    {
        $this->assertInstanceOf(RecipeId::class, $recipeId = RecipeId::generate());
        $this->assertTrue(Uuid::isValid($recipeId->__toString()));
    }

    /**
     * @dataProvider invalidUuidProvider
     * @testdox Can't create RecipeId with $invalidUuid
     *
     * @param string $invalidUuid
     */
    public function testCantCreateFromInvalidUuidString(string $invalidUuid): void
    {
        $this->expectException(InvalidUuidStringException::class);
        RecipeId::fromString($invalidUuid);
    }

    public function invalidUuidProvider(): array
    {
        return [
            [uniqid()],
            ['000000000000000000000000000000000000'],
        ];
    }
}
