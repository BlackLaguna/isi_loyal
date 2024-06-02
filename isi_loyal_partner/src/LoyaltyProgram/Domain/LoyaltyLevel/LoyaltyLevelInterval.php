<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain\LoyaltyLevel;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class LoyaltyLevelInterval
{
    #[ORM\Column(type: 'integer')]
    public int $start;

    #[ORM\Column(type: 'integer')]
    public int $end;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}