<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Tests;

use HelloFresh\Domain\Difficulty;
use HelloFresh\Domain\Exception\OutOfRangeDifficultyLevelException;
use PHPUnit\Framework\TestCase;

final class DifficultyTest extends TestCase
{
    /**
     * @dataProvider validRangeValueProvider
     * @testdox Can create difficulty level with $validValue
     *
     * @param int $validValue
     */
    public function testCanCreateFromValidRangeOfLevels(int $validValue): void
    {
        $this->assertInstanceOf(Difficulty::class, $difficultLevel = Difficulty::fromInteger($validValue));
        $this->assertEquals($difficultLevel->toInteger(), $validValue);
    }

    /**
     * @dataProvider outOfRangeLevelsProvider
     * @testdox Can't create difficulty level with $outOfRangeValue
     *
     * @param int $outOfRangeValue
     */
    public function testCantCreateFromOutOfRangeLevels(int $outOfRangeValue): void
    {
        $this->expectException(OutOfRangeDifficultyLevelException::class);
        Difficulty::fromInteger($outOfRangeValue);
    }

    public function outOfRangeLevelsProvider(): array
    {
        return [
            [-2],
            [0],
            [4],
        ];
    }

    public function validRangeValueProvider(): array
    {
        $values = [];

        foreach (range(1, 3) as $value) {
            array_push($values, [$value]);
        }

        return $values;
    }
}
