<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\OutOfRangeDifficultyLevelException;

final class Difficulty
{
    /** @var int */
    const LEVEL_MIN = 1;

    /** @var int */
    const LEVEL_MAX = 3;

    /** @var int */
    private $level;

    private function __construct()
    {
    }

    public static function fromInteger(int $level): Difficulty
    {
        $instance = new self();

        if ($level < $instance::LEVEL_MIN || $level > $instance::LEVEL_MAX) {
            throw OutOfRangeDifficultyLevelException::withLevel($level);
        }

        $instance->level = $level;

        return $instance;
    }

    public function toInteger(): int
    {
        return $this->level;
    }
}
