<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Command;

use LoyaltyProgram\Domain\LoyaltyProgram;
use Symfony\Component\Uid\AbstractUid;

final readonly class DeleteLoyaltyLevelToLoyaltyProgramCommand
{
    public function __construct(
        public LoyaltyProgram $loyaltyProgram,
        public AbstractUid $loyaltyLevelId,
    ) {
    }
}