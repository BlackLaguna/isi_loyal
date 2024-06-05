<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Command;

use LoyaltyProgram\Domain\Partner;

readonly class CreateLoyaltyProgramCommand
{
    public function __construct(
        public Partner $partner,
        public string $loyaltyProgramName,
    ) {
    }
}