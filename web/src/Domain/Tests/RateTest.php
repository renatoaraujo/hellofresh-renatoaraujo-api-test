<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\Exception\OutOfRangeRateException;
use HelloFresh\Domain\Rate;
use PHPUnit\Framework\TestCase;

final class RateTest extends TestCase
{
    /**
     * @dataProvider validRateProvider
     * @testdox Can rate with $rate
     */
    public function testCanCreateFromFloat(float $rate): void
    {
        $this->assertInstanceOf(Rate::class, $recipeName = Rate::fromFloat($rate));
        $this->assertEquals($recipeName->toFloat(), $rate);
    }

    /**
     * @dataProvider invalidRangeValueProvider
     * @testdox Can't rate with $rate
     *
     * @param float $rate
     */
    public function testCantRateWithOutOfRangeValues(float $rate): void
    {
        $this->expectException(OutOfRangeRateException::class);
        Rate::fromFloat($rate);
    }

    public function invalidRangeValueProvider(): array
    {
        return [[0], [6], [10], [-1]];
    }

    public function validRateProvider(): array
    {
        return [[1], [2], [3], [4], [5]];
    }
}
