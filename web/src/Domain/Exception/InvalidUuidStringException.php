<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Exception;

final class InvalidUuidStringException extends \DomainException
{
    public static function withUuidString(string $uuid): InvalidUuidStringException
    {
        return new self(
            sprintf('Invalid UUID: %s.', $uuid)
        );
    }
}
