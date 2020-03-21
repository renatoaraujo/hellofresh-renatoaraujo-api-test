<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Exception;

use HelloFresh\Domain\Rate;

final class OutOfRangeRateException extends \DomainException
{
    public static function withValue(float $level): OutOfRangeRateException
    {
        return new self(
            sprintf(
                'Rate "%d" out of range, accepted only from %d to %d.',
                $level,
                Rate::VALUE_MIN,
                Rate::VALUE_MAX
            )
        );
    }
}
