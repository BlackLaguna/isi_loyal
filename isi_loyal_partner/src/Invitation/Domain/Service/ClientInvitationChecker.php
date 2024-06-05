<?php

declare(strict_types=1);

namespace Invitation\Domain\Service;

use Invitation\Domain\ClientEmail;
use Invitation\Domain\LoyaltyProgram;

interface ClientInvitationChecker
{
    public function isClientAlreadyInvited(LoyaltyProgram $loyaltyProgram, ClientEmail $clientEmail): bool;
}