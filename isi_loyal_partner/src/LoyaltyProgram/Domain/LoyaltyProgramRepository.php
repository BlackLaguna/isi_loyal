<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain;


interface LoyaltyProgramRepository
{
    public function findAllForPartner(Partner $partner): array;
    public function findByPartnerAndName(Partner $partner, string $loyaltyProgramName): LoyaltyProgram;
    public function persist(LoyaltyProgram $loyaltyProgram): void;
}