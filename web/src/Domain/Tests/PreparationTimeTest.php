<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\PreparationTime;
use PHPUnit\Framework\TestCase;

final class PreparationTimeTest extends TestCase
{
    /**
     * @dataProvider positiveMinutesProvider
     * @testdox Can create PreparationTime with $minutes minutes
     *
     * @param int $minutes
     */
    public function testCanCreatePreparationTimeFromMinutesInteger(int $minutes): void
    {
        $this->assertInstanceOf(PreparationTime::class,
            $preparationTime = PreparationTime::fromInteger($minutes));
        $this->assertSame($minutes, $preparationTime->toMinutesInteger());
    }

    /**
     * @dataProvider negativeMinutesProvider
     * @expectedException \HelloFresh\Domain\Exception\NegativeMinutesNotAllowedException
     * @testdox Can't create PreparationTime with $negativeNumber minutes
     *
     * @param int $negativeNumber
     */
    public function testCantCreatePreparationTimeFromMinutesIntegerWithNegativeNumbers(int $negativeNumber): void
    {
        PreparationTime::fromInteger($negativeNumber);
    }

    public function positiveMinutesProvider(): array
    {
        $values = [];

        foreach (range(10, 15) as $value) {
            array_push($values, [$value]);
        }

        return $values;
    }

    public function negativeMinutesProvider(): array
    {
        $values = [];

        foreach (range(-5, 0) as $value) {
            array_push($values, [$value]);
        }

        return $values;
    }
}
