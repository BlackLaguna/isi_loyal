<?php

declare(strict_types = 1);

namespace LoyaltyProgram\Domain\Service;

use Symfony\Component\Uid\AbstractUid;

interface RecalculateClientsLoyaltyLevels
{
    public function __invoke(AbstractUid $loyaltyProgramUuid);
}