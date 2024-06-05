<?php

declare(strict_types=1);

namespace Invitation\Application\CQRS\Command;

use Invitation\Domain\ClientEmail;
use Invitation\Domain\LoyaltyProgram;
use Invitation\Domain\Partner;

final readonly class InviteClientToLoyaltyProgramCommand
{
    public function __construct(
        public Partner $partner,
        public LoyaltyProgram $loyaltyProgram,
        public ClientEmail $client,
    ) {
    }
}