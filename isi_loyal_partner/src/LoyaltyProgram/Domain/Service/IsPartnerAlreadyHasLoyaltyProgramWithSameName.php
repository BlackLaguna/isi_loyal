<?php

namespace LoyaltyProgram\Domain\Service;

use LoyaltyProgram\Domain\Partner;

interface IsPartnerAlreadyHasLoyaltyProgramWithSameName
{
    public function __invoke(Partner $partner, string $loyaltyProgramName): bool;
}