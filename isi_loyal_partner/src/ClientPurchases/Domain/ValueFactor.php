<?php

declare(strict_types=1);

namespace ClientPurchases\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ValueFactor
{
    public function __construct(
        #[ORM\Column(type: 'integer')]
        public int $valueFactor
    ) {
    }

    public function increaseBy(self $valueFactor): void
    {
        $this->valueFactor += $valueFactor->valueFactor;
    }

    public static function createFromInt(int $valueFactor): self
    {
        return new self($valueFactor);
    }

    public function isGreaterOrEqual(self $valueFactor): bool
    {
        return $this->valueFactor >= $valueFactor->valueFactor;
    }
}