<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain\LoyaltyLevel;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ValueFactor
{
    public function __construct(
        #[ORM\Column(type: 'integer')]
        public int $valueFactor
    ) {
    }

    public static function createFromInt(int $valueFactor)
    {
        return new self($valueFactor);
    }

    public function isEqual(self $valueFactor): bool
    {
        return $this->valueFactor === $valueFactor->valueFactor;
    }

    public function isGreaterOrEqual(self $valueFactor): bool
    {
        return $this->valueFactor >= $valueFactor->valueFactor;
    }
}