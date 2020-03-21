<?php
declare(strict_types=1);

namespace HelloFresh\Domain;

use HelloFresh\Domain\Exception\InvalidUuidStringException;

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

        $pattern = '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$';

        if (!preg_match('/' . $pattern . '/D', $recipeId) || $recipeId === '00000000-0000-0000-0000-000000000000') {
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

        $instance->uuid = implode('-', [
            \bin2hex(\random_bytes(4)),
            \bin2hex(\random_bytes(2)),
            \bin2hex(\chr((\ord(\random_bytes(1)) & 0x0F) | 0x40)) . \bin2hex(\random_bytes(1)),
            \bin2hex(\chr((\ord(\random_bytes(1)) & 0x3F) | 0x80)) . \bin2hex(\random_bytes(1)),
            \bin2hex(\random_bytes(6)),
        ]);

        return $instance;
    }
}
