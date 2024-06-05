<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain;

use Symfony\Component\Uid\Uuid;

interface LoyaltyProgramRepository
{
    public function findByPartnerAndName(Partner $partner, string $loyaltyProgramName): LoyaltyProgram;
    public function persist(LoyaltyProgram $loyaltyProgram): void;
}