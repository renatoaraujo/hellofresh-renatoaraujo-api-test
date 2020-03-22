<?php
declare(strict_types=1);

namespace HelloFresh\Domain\Command;

final class ListRecipes
{
    /** @var array */
    private $criteria;

    public function __construct(array $criteria = [])
    {
        $this->criteria = $criteria;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
}
