<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Exception;

final class NameCannotBeEmptyException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Recipe name cannot be empty.');
    }
}
