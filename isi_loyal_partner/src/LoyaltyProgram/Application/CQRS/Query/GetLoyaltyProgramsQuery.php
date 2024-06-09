<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Query;

use LoyaltyProgram\Domain\Partner;

final readonly class GetLoyaltyProgramsQuery
{
    public function __construct(
        public Partner $partner
    ) {
    }
}