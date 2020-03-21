<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\NameCannotBeEmptyException;

final class Name
{
    /** @var string */
    private $name;

    private function __construct()
    {
    }

    public static function fromString(string $name): Name
    {
        $instance = new self();

        if ('' === $name) {
            throw new NameCannotBeEmptyException();
        }

        $instance->name = $name;

        return $instance;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
