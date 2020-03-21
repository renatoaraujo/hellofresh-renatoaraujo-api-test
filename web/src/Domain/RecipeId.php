<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

final class RecipeId
{
    /** @var string */
    private $uuid;

    private function __construct()
    {
    }

    public static function fromString(string $recipeId): RecipeId
    {
        $instance = new self();

        if (Uuid::isValid($recipeId) !== true) {
            throw InvalidUuidStringException::withUuidString($recipeId);
        }

        $instance->uuid = $recipeId;

        return $instance;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public static function generate(): RecipeId
    {
        $instance = new self();
        $instance->uuid = Uuid::uuid4()->toString();

        return $instance;
    }
}
