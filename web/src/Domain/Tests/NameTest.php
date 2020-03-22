<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\Exception\NameCannotBeEmptyException;
use HelloFresh\Domain\Name;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testCanCreateFromString(): void
    {
        $name = 'Herby Pan-Seared Chicken';
        $this->assertInstanceOf(Name::class, $recipeName = Name::fromString($name));
        $this->assertEquals($recipeName->__toString(), $name);
    }

    /**
     * @testdox Can't create name with empty value
     */
    public function testCantCreateNameWithEmptyValue(): void
    {
        $this->expectException(NameCannotBeEmptyException::class);
        Name::fromString('');
    }
}
