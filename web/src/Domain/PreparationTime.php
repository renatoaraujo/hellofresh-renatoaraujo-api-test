<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\NegativeMinutesNotAllowedException;

final class PreparationTime
{
    /** @var int */
    private $minutes;

    private function __construct()
    {
    }

    public static function fromInteger(int $minutes): PreparationTime
    {
        $instance = new self();

        if ($minutes <= 0) {
            throw NegativeMinutesNotAllowedException::withNumber($minutes);
        }

        $instance->minutes = $minutes;

        return $instance;
    }

    public function toMinutesInteger(): int
    {
        return $this->minutes;
    }
}
