<?php

declare(strict_types=1);

namespace ClientPurchases\Application\CQRS\Query;

use ClientPurchases\Domain\Client;
use ClientPurchases\Domain\LoyaltyProgram;

final readonly class GetClientLoyaltyProgramStatisticsQuery
{
    public function __construct(
        public Client $client,
        public LoyaltyProgram $loyaltyProgram
    ) {
    }
}