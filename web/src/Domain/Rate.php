<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\OutOfRangeRateException;

final class Rate
{
    /** @var int */
    const VALUE_MIN = 1;

    /** @var int */
    const VALUE_MAX = 5;

    /** @var int */
    private $value;

    private function __construct()
    {
    }

    public static function fromFloat(float $value): Rate
    {
        $instance = new self();

        if ($value < $instance::VALUE_MIN || $value > $instance::VALUE_MAX) {
            throw OutOfRangeRateException::withValue($value);
        }

        $instance->value = $value;

        return $instance;
    }

    public function toFloat(): float
    {
        return $this->value;
    }
}
