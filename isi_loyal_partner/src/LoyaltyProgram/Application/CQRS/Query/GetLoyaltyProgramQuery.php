<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Query;

use LoyaltyProgram\Domain\Partner;

readonly class GetLoyaltyProgramQuery
{
    public function __construct(
        public Partner $partner,
        public string $loyaltyProgramName
    ) {
    }
}