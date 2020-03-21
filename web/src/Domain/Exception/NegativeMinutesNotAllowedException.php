<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Exception;

final class NegativeMinutesNotAllowedException extends \DomainException
{
    public static function withNumber(int $number) : NegativeMinutesNotAllowedException
    {
        return new self(
            sprintf(
                'Negative numbers such as "%d" for preparation time are not allowed.',
                $number
            )
        );
    }
}
