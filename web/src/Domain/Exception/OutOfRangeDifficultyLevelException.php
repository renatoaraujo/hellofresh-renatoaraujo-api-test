<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Exception;

use HelloFresh\Domain\Difficulty;

final class OutOfRangeDifficultyLevelException extends \DomainException
{
    public static function withLevel(int $level) : OutOfRangeDifficultyLevelException
    {
        return new self(
            sprintf(
                'Invalid range for difficulty level %d, accepted only from %d to %d.',
                $level,
                Difficulty::LEVEL_MIN,
                Difficulty::LEVEL_MAX
            )
        );
    }
}
