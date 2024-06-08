<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Command;

use LoyaltyProgram\Domain\LoyaltyProgram;

final readonly class AddLoyaltyLevelToLoyaltyProgramCommand
{
    public function __construct(
        public LoyaltyProgram $loyaltyProgram,
        public string $loyaltyLevelName,
        public int $loyaltyLevelValueFactor,
    ) {
    }
}