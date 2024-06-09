<?php

declare(strict_types=1);

namespace ClientPurchases\Application\CQRS\Command;

use ClientPurchases\Domain\Client;
use ClientPurchases\Domain\LoyaltyProgram;
use ClientPurchases\Domain\ValueFactor;

final readonly class AddClientPurchasesCommand
{
    public function __construct(
        public Client $client,
        public LoyaltyProgram $loyaltyProgram,
        public ValueFactor $valueFactor
    ) {
    }
}